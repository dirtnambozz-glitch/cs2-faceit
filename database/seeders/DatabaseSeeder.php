<?php

namespace Database\Seeders;

use App\Models\GameMap;
use App\Models\User;
use App\Services\EloService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed CS2 Competitive Active Maps
        $maps = [
            [
                'name' => 'Mirage',
                'code' => 'de_mirage',
                'image' => '/images/maps/de_mirage.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Inferno',
                'code' => 'de_inferno',
                'image' => '/images/maps/de_inferno.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Nuke',
                'code' => 'de_nuke',
                'image' => '/images/maps/de_nuke.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Dust II',
                'code' => 'de_dust2',
                'image' => '/images/maps/de_dust2.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Ancient',
                'code' => 'de_ancient',
                'image' => '/images/maps/de_ancient.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Anubis',
                'code' => 'de_anubis',
                'image' => '/images/maps/de_anubis.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Vertigo',
                'code' => 'de_vertigo',
                'image' => '/images/maps/de_vertigo.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($maps as $map) {
            GameMap::updateOrCreate(['code' => $map['code']], $map);
        }

        // 2. Demo User (The Main User logged in by default)
        User::updateOrCreate(
            ['email' => 'player@faceit.test'],
            [
                'name' => 'PlayerOne',
                'password' => Hash::make('password'),
                'steam_id' => '76561198000000001',
                'elo' => 1050,
                'level' => EloService::calculateLevel(1050),
                'matches_count' => 12,
                'wins_count' => 7,
                'losses_count' => 5,
                'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=PlayerOne',
                'is_bot' => false,
            ]
        );

        // 3. Test Bot for instant 1v1 match testing
        User::updateOrCreate(
            ['email' => 'bot@faceit.test'],
            [
                'name' => 'TestOpponentBot',
                'password' => Hash::make('password'),
                'steam_id' => '76561198000000002',
                'elo' => 1020,
                'level' => EloService::calculateLevel(1020),
                'matches_count' => 8,
                'wins_count' => 4,
                'losses_count' => 4,
                'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=TestOpponentBot',
                'is_bot' => true,
            ]
        );

        // 4. Pro Players for Leaderboard
        $proPlayers = [
            ['name' => 'donk', 'elo' => 2680, 'matches' => 180, 'wins' => 135],
            ['name' => 's1mple', 'elo' => 2520, 'matches' => 210, 'wins' => 150],
            ['name' => 'm0NESY', 'elo' => 2480, 'matches' => 195, 'wins' => 140],
            ['name' => 'ZywOo', 'elo' => 2450, 'matches' => 190, 'wins' => 133],
            ['name' => 'b1t', 'elo' => 2150, 'matches' => 140, 'wins' => 95],
            ['name' => 'kyousuke', 'elo' => 1920, 'matches' => 85, 'wins' => 55],
            ['name' => 'fROSTy', 'elo' => 1740, 'matches' => 70, 'wins' => 42],
            ['name' => 'k3nnY', 'elo' => 1520, 'matches' => 60, 'wins' => 33],
            ['name' => 'v1per', 'elo' => 1320, 'matches' => 45, 'wins' => 23],
            ['name' => 'rookie_99', 'elo' => 880, 'matches' => 20, 'wins' => 7],
        ];

        foreach ($proPlayers as $p) {
            User::updateOrCreate(
                ['email' => strtolower($p['name']) . '@faceit.test'],
                [
                    'name' => $p['name'],
                    'password' => Hash::make('password'),
                    'steam_id' => '76561198' . rand(100000000, 999999999),
                    'elo' => $p['elo'],
                    'level' => EloService::calculateLevel($p['elo']),
                    'matches_count' => $p['matches'],
                    'wins_count' => $p['wins'],
                    'losses_count' => $p['matches'] - $p['wins'],
                    'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $p['name'],
                    'is_bot' => true,
                ]
            );
        }
    }
}
