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
                'avatar' => 'https://avatars.steamstatic.com/fef49e7fa7e1997310d705b2a6158ff8dc1cdfeb_full.jpg',
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
                'avatar' => 'https://avatars.steamstatic.com/b5bd56c1aa99e4404098939c3e981dc89a818e38_full.jpg',
                'is_bot' => true,
            ]
        );

        // 4. Pro Players for Leaderboard (Authentic Steam Avatars)
        $proPlayers = [
            ['name' => 'donk', 'elo' => 2680, 'matches' => 180, 'wins' => 135, 'avatar' => 'https://avatars.steamstatic.com/d0c156f7ef08c26ab8ba54f8b056ea02e7be59dc_full.jpg'],
            ['name' => 's1mple', 'elo' => 2520, 'matches' => 210, 'wins' => 150, 'avatar' => 'https://avatars.steamstatic.com/d946ce246e7293a5665eb8cb9ca43c080ef3dcbb_full.jpg'],
            ['name' => 'm0NESY', 'elo' => 2480, 'matches' => 195, 'wins' => 140, 'avatar' => 'https://avatars.steamstatic.com/2ce28f80cb421471017efad5bce866a2068fbf6e_full.jpg'],
            ['name' => 'ZywOo', 'elo' => 2450, 'matches' => 190, 'wins' => 133, 'avatar' => 'https://avatars.steamstatic.com/6a56fc001ca2d9b689ef7d07997579cae564dcb2_full.jpg'],
            ['name' => 'b1t', 'elo' => 2150, 'matches' => 140, 'wins' => 95, 'avatar' => 'https://avatars.steamstatic.com/4a1c5d0119e7a4f5cb13d1e1c15f403e9c7ea07a_full.jpg'],
            ['name' => 'kyousuke', 'elo' => 1920, 'matches' => 85, 'wins' => 55, 'avatar' => 'https://avatars.steamstatic.com/b28d4076ba54fb8ec042ec34cbf7d8481ff23398_full.jpg'],
            ['name' => 'fROSTy', 'elo' => 1740, 'matches' => 70, 'wins' => 42, 'avatar' => 'https://avatars.steamstatic.com/c15b1392652ce5fe66b88ef2b8f87455d3f23ff1_full.jpg'],
            ['name' => 'k3nnY', 'elo' => 1520, 'matches' => 60, 'wins' => 33, 'avatar' => 'https://avatars.steamstatic.com/e2d45c55be2fbb4829910d54c7ff3cbbdfa2a353_full.jpg'],
            ['name' => 'v1per', 'elo' => 1320, 'matches' => 45, 'wins' => 23, 'avatar' => 'https://avatars.steamstatic.com/839446d378ee0c6b162f4eec0317e6518ae34b3e_full.jpg'],
            ['name' => 'rookie_99', 'elo' => 880, 'matches' => 20, 'wins' => 7, 'avatar' => 'https://avatars.steamstatic.com/fef49e7fa7e1997310d705b2a6158ff8dc1cdfeb_full.jpg'],
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
                    'avatar' => $p['avatar'],
                    'is_bot' => true,
                ]
            );
        }
    }
}
