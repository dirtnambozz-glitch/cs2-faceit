<?php

namespace App\Services;

class EloService
{
    public const K_FACTOR = 32;

    public static function calculateLevel(int $elo): int
    {
        return match (true) {
            $elo < 801 => 1,
            $elo < 951 => 2,
            $elo < 1101 => 3,
            $elo < 1251 => 4,
            $elo < 1401 => 5,
            $elo < 1551 => 6,
            $elo < 1701 => 7,
            $elo < 1851 => 8,
            $elo < 2001 => 9,
            default => 10,
        };
    }

    public static function getLevelBadgeColor(int $level): string
    {
        return match ($level) {
            1 => 'bg-neutral-500 text-white',
            2 => 'bg-lime-600 text-white',
            3 => 'bg-emerald-600 text-white',
            4 => 'bg-amber-500 text-black',
            5 => 'bg-amber-600 text-white',
            6 => 'bg-orange-500 text-white',
            7 => 'bg-orange-600 text-white',
            8 => 'bg-red-500 text-white',
            9 => 'bg-red-600 text-white',
            10 => 'bg-gradient-to-r from-red-600 to-amber-500 text-white ring-2 ring-amber-400',
            default => 'bg-neutral-600 text-white',
        };
    }

    /**
     * Calculate Elo changes for both players.
     * Returns [$eloChangeP1, $eloChangeP2]
     * $scoreP1: 1 for win, 0 for loss, 0.5 for draw.
     */
    public static function calculateMatchElo(int $eloP1, int $eloP2, float $scoreP1): array
    {
        $expectedP1 = 1 / (1 + pow(10, ($eloP2 - $eloP1) / 400));
        $expectedP2 = 1 - $expectedP1;

        $scoreP2 = 1 - $scoreP1;

        $changeP1 = (int) round(self::K_FACTOR * ($scoreP1 - $expectedP1));
        $changeP2 = (int) round(self::K_FACTOR * ($scoreP2 - $expectedP2));

        // Ensure minimum change of 1 if win/loss
        if ($scoreP1 == 1 && $changeP1 <= 0) $changeP1 = 1;
        if ($scoreP1 == 0 && $changeP1 >= 0) $changeP1 = -1;
        if ($scoreP2 == 1 && $changeP2 <= 0) $changeP2 = 1;
        if ($scoreP2 == 0 && $changeP2 >= 0) $changeP2 = -1;

        return [$changeP1, $changeP2];
    }
}
