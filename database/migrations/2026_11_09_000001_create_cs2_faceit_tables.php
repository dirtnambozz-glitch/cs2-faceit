<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('steam_id')->nullable()->after('email');
            $table->unsignedInteger('elo')->default(1000)->after('steam_id');
            $table->unsignedTinyInteger('level')->default(3)->after('elo');
            $table->unsignedInteger('matches_count')->default(0)->after('level');
            $table->unsignedInteger('wins_count')->default(0)->after('matches_count');
            $table->unsignedInteger('losses_count')->default(0)->after('wins_count');
            $table->string('avatar')->nullable()->after('losses_count');
            $table->boolean('is_bot')->default(false)->after('avatar');
        });

        Schema::create('game_maps', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Mirage, Inferno, Dust II...
            $table->string('code')->unique(); // de_mirage, de_inferno...
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('game_matches', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('player1_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('player2_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('map_id')->nullable()->constrained('game_maps')->nullOnDelete();
            $table->enum('status', ['searching', 'voting', 'ready', 'in_progress', 'finished', 'cancelled'])->default('searching');
            $table->foreignId('current_turn_player_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('score_player1')->default(0);
            $table->unsignedTinyInteger('score_player2')->default(0);
            $table->integer('elo_change_p1')->nullable();
            $table->integer('elo_change_p2')->nullable();
            $table->string('server_ip')->nullable();
            $table->unsignedSmallInteger('server_port')->nullable();
            $table->string('server_password')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('match_vetoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('game_matches')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('map_id')->constrained('game_maps')->cascadeOnDelete();
            $table->enum('action', ['ban', 'pick'])->default('ban');
            $table->unsignedTinyInteger('step')->default(1);
            $table->timestamps();
        });

        Schema::create('matchmaking_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('elo')->default(1000);
            $table->enum('status', ['searching', 'matched', 'accepted'])->default('searching');
            $table->foreignId('match_id')->nullable()->constrained('game_matches')->nullOnDelete();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matchmaking_queues');
        Schema::dropIfExists('match_vetoes');
        Schema::dropIfExists('game_matches');
        Schema::dropIfExists('game_maps');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'steam_id',
                'elo',
                'level',
                'matches_count',
                'wins_count',
                'losses_count',
                'avatar',
                'is_bot'
            ]);
        });
    }
};
