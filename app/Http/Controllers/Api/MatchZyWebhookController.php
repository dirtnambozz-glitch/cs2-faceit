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

        // Map end or series end
        if (in_array($event, ['map_result', 'series_end', 'match_end'])) {
            $matchId = $payload['matchid'] ?? $payload['match_id'] ?? null;
            $match = null;

            if ($matchId) {
                $match = GameMatch::where('uuid', $matchId)->orWhere('id', $matchId)->first();
            }

            if (! $match) {
                // Find latest in_progress or ready match
                $match = GameMatch::whereIn('status', ['ready', 'in_progress'])->latest()->first();
            }

            if ($match && $match->status !== 'finished') {
                $score1 = (int) ($payload['team1']['score'] ?? $payload['team1_score'] ?? 0);
                $score2 = (int) ($payload['team2']['score'] ?? $payload['team2_score'] ?? 0);

                $winnerId = ($score1 > $score2) ? $match->player1_id : $match->player2_id;
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
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
