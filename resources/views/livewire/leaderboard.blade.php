<div>
    <!-- Leaderboard Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-faceit font-bold text-white uppercase tracking-wider">
                Таблица лидеров CS2 (Leaderboard)
            </h1>
            <p class="text-neutral-400 text-sm mt-1">
                Рейтинг сильнейших игроков платформы по системе Elo
            </p>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Поиск игрока..." 
                class="w-full sm:w-64 bg-faceit-card border border-faceit-border rounded-xl px-4 py-2.5 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-faceit-orange">

            <select 
                wire:model.live="levelFilter" 
                class="w-full sm:w-auto bg-faceit-card border border-faceit-border rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-faceit-orange">
                <option value="all">Все уровни</option>
                @for($lvl = 10; $lvl >= 1; $lvl--)
                    <option value="{{ $lvl }}">Уровень {{ $lvl }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Leaderboard Table Card -->
    <div class="bg-faceit-card border border-faceit-border rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-faceit-border bg-neutral-900/60 text-[11px] font-semibold uppercase tracking-wider text-neutral-400">
                        <th class="py-4 px-6 text-center w-16">Ранг</th>
                        <th class="py-4 px-6">Игрок</th>
                        <th class="py-4 px-6 text-center w-28">Уровень</th>
                        <th class="py-4 px-6 text-right">Рейтинг Elo</th>
                        <th class="py-4 px-6 text-right">Матчей</th>
                        <th class="py-4 px-6 text-right">Винрейт</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-faceit-border/60 text-sm">
                    @forelse($users as $index => $u)
                        @php
                            $rank = $users->firstItem() + $index;
                        @endphp
                        <tr class="hover:bg-neutral-800/40 transition {{ auth()->id() === $u->id ? 'bg-faceit-orange/10 border-l-4 border-l-faceit-orange' : '' }}">
                            <!-- Rank Number -->
                            <td class="py-4 px-6 text-center font-faceit font-bold text-base">
                                @if($rank === 1)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400/20 text-amber-400 border border-amber-400/40 shadow">
                                        🥇
                                    </span>
                                @elseif($rank === 2)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-300/20 text-slate-300 border border-slate-300/40 shadow">
                                        🥈
                                    </span>
                                @elseif($rank === 3)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-700/20 text-amber-600 border border-amber-700/40 shadow">
                                        🥉
                                    </span>
                                @else
                                    <span class="text-neutral-400">#{{ $rank }}</span>
                                @endif
                            </td>

                            <!-- Player Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $u->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $u->name }}" class="w-9 h-9 rounded-full bg-neutral-800 border border-faceit-border" alt="Avatar">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold text-white {{ auth()->id() === $u->id ? 'text-faceit-orange' : '' }}">
                                                {{ $u->name }}
                                            </span>
                                            @if(auth()->id() === $u->id)
                                                <span class="text-[10px] uppercase font-bold bg-faceit-orange text-white px-1.5 py-0.2 rounded">Вы</span>
                                            @endif
                                            @if($u->is_bot)
                                                <span class="text-[9px] uppercase font-semibold bg-neutral-800 text-neutral-400 px-1 py-0.2 rounded">Bot</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-neutral-500 font-mono">
                                            {{ $u->steam_id ? 'Steam: ' . $u->steam_id : 'ID: ' . $u->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Level Circle Badge -->
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex w-7 h-7 rounded-full items-center justify-center font-faceit font-bold text-sm shadow {{ $u->level_badge_color }}">
                                    {{ $u->level }}
                                </span>
                            </td>

                            <!-- Elo -->
                            <td class="py-4 px-6 text-right font-mono font-bold text-base text-white">
                                {{ $u->elo }}
                            </td>

                            <!-- Matches count -->
                            <td class="py-4 px-6 text-right font-mono text-neutral-300">
                                {{ $u->matches_count }}
                                <span class="text-xs text-neutral-500">({{ $u->wins_count }}В / {{ $u->losses_count }}П)</span>
                            </td>

                            <!-- Winrate -->
                            <td class="py-4 px-6 text-right font-mono font-semibold {{ $u->winrate >= 50 ? 'text-emerald-400' : 'text-neutral-400' }}">
                                {{ $u->winrate }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-neutral-500">
                                Игроки не найдены
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-faceit-border">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
