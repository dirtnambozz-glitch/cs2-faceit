<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Services\EloService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MatchZyWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $event = $payload['event'] ?? $payload['type'] ?? 'unknown';

        Log::info("MatchZy Webhook received [{$event}]:", $payload);

        // Find the corresponding match in our system
        $match = $this->findTargetMatch($payload);

        if (! $match) {
            Log::warning("MatchZy Webhook [{$event}]: No active match found for payload.", [
                'matchid' => $payload['matchid'] ?? null,
                'team1' => $payload['team1']['name'] ?? null,
                'team2' => $payload['team2']['name'] ?? null,
            ]);
            return response()->json(['status' => 'ignored', 'reason' => 'no_active_match']);
        }

        Log::info("MatchZy Webhook [{$event}]: Matched to GameMatch #{$match->id} (UUID: {$match->uuid}, Status: {$match->status})");

        // 1. Live Match Start
        if (in_array($event, ['going_live', 'match_start'])) {
            if ($match->status !== 'finished') {
                $match->update([
                    'status' => 'in_progress',
                    'started_at' => $match->started_at ?? now(),
                ]);
            }
            return response()->json(['status' => 'ok']);
        }

        // 2. Round End -> Live score updates
        if ($event === 'round_end') {
            if ($match->status !== 'finished') {
                [$score1, $score2] = $this->extractScores($match, $payload);
                $match->update([
                    'status' => 'in_progress',
                    'score_player1' => $score1,
                    'score_player2' => $score2,
                ]);
                Log::info("Match #{$match->id} live score updated: {$score1} : {$score2}");
            }
            return response()->json(['status' => 'ok']);
        }

        // 3. Map Result or Series End -> Finish the match and calculate Elo
        if (in_array($event, ['map_result', 'series_end', 'match_end'])) {
            if ($match->status !== 'finished') {
                [$score1, $score2] = $this->extractScores($match, $payload);

                // If scores are 0 in series_end, fallback to existing scores from round_end
                if ($score1 === 0 && $score2 === 0 && ($match->score_player1 > 0 || $match->score_player2 > 0)) {
                    $score1 = $match->score_player1;
                    $score2 = $match->score_player2;
                }

                $winnerId = ($score1 > $score2) ? $match->player1_id : (($score2 > $score1) ? $match->player2_id : null);
                $score1Norm = ($score1 > $score2) ? 1.0 : (($score1 === $score2) ? 0.5 : 0.0);

                $p1 = $match->player1;
                $p2 = $match->player2;

                if ($p1 && $p2) {
                    [$elo1, $elo2] = EloService::calculateMatchElo($p1->elo, $p2->elo, $score1Norm);

                    $p1->elo = max(100, $p1->elo + $elo1);
                    $p1->matches_count++;
                    if ($score1Norm == 1.0) $p1->wins_count++; else $p1->losses_count++;
                    $p1->updateLevelFromElo();

                    $p2->elo = max(100, $p2->elo + $elo2);
                    $p2->matches_count++;
                    if ($score1Norm == 0.0) $p2->wins_count++; else $p2->losses_count++;
                    $p2->updateLevelFromElo();

                    $match->update([
                        'status' => 'finished',
                        'score_player1' => $score1,
                        'score_player2' => $score2,
                        'winner_id' => $winnerId,
                        'elo_change_p1' => $elo1,
                        'elo_change_p2' => $elo2,
                        'finished_at' => now(),
                    ]);

                    Log::info("Match #{$match->id} FINISHED via MatchZy! Winner: {$winnerId}, Final Score: {$score1}:{$score2}, Elo changes: [P1: {$elo1}, P2: {$elo2}]");
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Smart match finder: checks SteamIDs, player nicknames, UUIDs, and active status.
     */
    private function findTargetMatch(array $payload): ?GameMatch
    {
        // 1. Extract SteamIDs from team1 / team2 / attacker / player
        $steamIds = [];
        foreach (['team1', 'team2'] as $t) {
            if (!empty($payload[$t]['players']) && is_array($payload[$t]['players'])) {
                foreach ($payload[$t]['players'] as $p) {
                    if (!empty($p['steamid'])) {
                        $steamIds[] = (string) $p['steamid'];
                    }
                }
            }
        }
        if (!empty($payload['attacker']['steamid'])) $steamIds[] = (string) $payload['attacker']['steamid'];
        if (!empty($payload['player']['steamid'])) $steamIds[] = (string) $payload['player']['steamid'];
        $steamIds = array_unique(array_filter($steamIds));

        // Find ACTIVE match by SteamID
        if (!empty($steamIds)) {
            $match = GameMatch::whereIn('status', ['ready', 'in_progress', 'voting'])
                ->where(function ($q) use ($steamIds) {
                    $q->whereHas('player1', fn ($p) => $p->whereIn('steam_id', $steamIds))
                      ->orWhereHas('player2', fn ($p) => $p->whereIn('steam_id', $steamIds));
                })
                ->latest('id')
                ->first();

            if ($match) {
                return $match;
            }
        }

        // 2. Extract player nicknames from team names (e.g. "team_defense" -> "defense")
        $names = [];
        foreach (['team1', 'team2'] as $t) {
            if (!empty($payload[$t]['name'])) {
                $clean = trim(preg_replace('/^team_/i', '', $payload[$t]['name']));
                if (!empty($clean)) {
                    $names[] = strtolower($clean);
                }
            }
        }

        if (!empty($names)) {
            $match = GameMatch::whereIn('status', ['ready', 'in_progress', 'voting'])
                ->where(function ($q) use ($names) {
                    $q->whereHas('player1', fn ($p) => $p->whereRaw('LOWER(name) IN (' . implode(',', array_fill(0, count($names), '?')) . ')', $names))
                      ->orWhereHas('player2', fn ($p) => $p->whereRaw('LOWER(name) IN (' . implode(',', array_fill(0, count($names), '?')) . ')', $names));
                })
                ->latest('id')
                ->first();

            if ($match) {
                return $match;
            }
        }

        // 3. Check if matchid matches UUID (if custom UUID was supplied)
        $rawId = $payload['matchid'] ?? $payload['match_id'] ?? null;
        if ($rawId && strlen((string)$rawId) > 10) {
            $match = GameMatch::where('uuid', (string) $rawId)
                ->whereIn('status', ['ready', 'in_progress', 'voting'])
                ->first();
            if ($match) {
                return $match;
            }
        }

        // 4. Fallback: Most recent in_progress or ready match
        $match = GameMatch::whereIn('status', ['ready', 'in_progress'])
            ->latest('id')
            ->first();

        if ($match) {
            return $match;
        }

        // 5. Fallback: Most recent voting match
        return GameMatch::where('status', 'voting')
            ->latest('id')
            ->first();
    }

    /**
     * Map team1/team2 scores to player1/player2 based on SteamID or username.
     */
    private function extractScores(GameMatch $match, array $payload): array
    {
        $team1Score = (int) ($payload['team1']['score'] ?? $payload['team1_score'] ?? 0);
        $team2Score = (int) ($payload['team2']['score'] ?? $payload['team2_score'] ?? 0);

        $p1Steam = (string) ($match->player1 ? $match->player1->steam_id : '');
        $p1Name = strtolower((string) ($match->player1 ? $match->player1->name : ''));

        $team2SteamIds = [];
        if (!empty($payload['team2']['players']) && is_array($payload['team2']['players'])) {
            foreach ($payload['team2']['players'] as $p) {
                if (!empty($p['steamid'])) $team2SteamIds[] = (string) $p['steamid'];
            }
        }
        $team2Name = strtolower(trim(preg_replace('/^team_/i', '', $payload['team2']['name'] ?? '')));

        // If player 1 is on team 2, swap scores so player1 gets team2's score
        if (($p1Steam && in_array($p1Steam, $team2SteamIds)) || ($p1Name && $team2Name === $p1Name)) {
            return [$team2Score, $team1Score];
        }

        // Default: player 1 is team 1, player 2 is team 2
        return [$team1Score, $team2Score];
    }
}
