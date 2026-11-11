<?php

use App\Models\GameMap;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $mapImages = [
            'de_mirage' => '/images/maps/de_mirage.jpg',
            'de_inferno' => '/images/maps/de_inferno.jpg',
            'de_nuke' => '/images/maps/de_nuke.jpg',
            'de_dust2' => '/images/maps/de_dust2.jpg',
            'de_ancient' => '/images/maps/de_ancient.jpg',
            'de_anubis' => '/images/maps/de_anubis.jpg',
            'de_vertigo' => '/images/maps/de_vertigo.jpg',
        ];

        foreach ($mapImages as $code => $image) {
            GameMap::where('code', $code)->update(['image' => $image]);
        }
    }

    public function down(): void
    {
    }
};
