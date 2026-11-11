<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'banned_until')) {
                $table->timestamp('banned_until')->nullable()->after('is_admin');
            }
            if (! Schema::hasColumn('users', 'ban_reason')) {
                $table->string('ban_reason')->nullable()->after('banned_until');
            }
            if (! Schema::hasColumn('users', 'warnings_count')) {
                $table->unsignedInteger('warnings_count')->default(0)->after('ban_reason');
            }
        });

        Schema::table('game_matches', function (Blueprint $table) {
            if (! Schema::hasColumn('game_matches', 'player1_accepted')) {
                $table->boolean('player1_accepted')->default(false)->after('current_turn_player_id');
            }
            if (! Schema::hasColumn('game_matches', 'player2_accepted')) {
                $table->boolean('player2_accepted')->default(false)->after('player1_accepted');
            }
            if (! Schema::hasColumn('game_matches', 'accept_deadline')) {
                $table->timestamp('accept_deadline')->nullable()->after('player2_accepted');
            }
            if (! Schema::hasColumn('game_matches', 'connect_deadline')) {
                $table->timestamp('connect_deadline')->nullable()->after('accept_deadline');
            }
            if (! Schema::hasColumn('game_matches', 'player1_connected')) {
                $table->boolean('player1_connected')->default(false)->after('connect_deadline');
            }
            if (! Schema::hasColumn('game_matches', 'player2_connected')) {
                $table->boolean('player2_connected')->default(false)->after('player1_connected');
            }
            if (! Schema::hasColumn('game_matches', 'forfeited_user_id')) {
                $table->foreignId('forfeited_user_id')->nullable()->constrained('users')->nullOnDelete()->after('winner_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['banned_until', 'ban_reason', 'warnings_count']);
        });

        Schema::table('game_matches', function (Blueprint $table) {
            $table->dropForeign(['forfeited_user_id']);
            $table->dropColumn([
                'player1_accepted',
                'player2_accepted',
                'accept_deadline',
                'connect_deadline',
                'player1_connected',
                'player2_connected',
                'forfeited_user_id',
            ]);
        });
    }
};
