<div>
    <!-- Leaderboard Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center space-x-2 text-xs font-mono uppercase tracking-widest text-[#FF0055] mb-1">
                <span class="w-2 h-2 rounded-full bg-[#FF0055] animate-ping"></span>
                <span>GLOBAL RANKINGS // ELO RATING</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-cyber font-extrabold text-white uppercase tracking-wider">
                ТАБЛИЦА ЛИДЕРОВ <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] to-[#FF2A6D]">RIVALIS</span>
            </h1>
            <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                Рейтинг сильнейших киберспортсменов CS2 по системе пересчета Elo
            </p>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Поиск игрока..." 
                class="w-full sm:w-64 bg-[#120817] border border-[#FF0055]/30 rounded-xl px-4 py-2.5 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-[#FF0055] font-mono">

            <select 
                wire:model.live="levelFilter" 
                class="w-full sm:w-auto bg-[#120817] border border-[#FF0055]/30 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#FF0055] font-mono">
                <option value="all">Все уровни (1-10)</option>
                @for($lvl = 10; $lvl >= 1; $lvl--)
                    <option value="{{ $lvl }}">Уровень {{ $lvl }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Leaderboard Table Card -->
    <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl overflow-hidden shadow-[0_0_35px_rgba(255,0,85,0.2)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#FF0055]/20 bg-[#08040A] text-[11px] font-mono font-bold uppercase tracking-wider text-[#FF0055]">
                        <th class="py-4 px-6 text-center w-20">Ранг</th>
                        <th class="py-4 px-6">Игрок</th>
                        <th class="py-4 px-6 text-center w-28">Уровень</th>
                        <th class="py-4 px-6 text-right">Рейтинг Elo</th>
                        <th class="py-4 px-6 text-right">Матчей</th>
                        <th class="py-4 px-6 text-right">Винрейт</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#FF0055]/15 text-sm">
                    @forelse($users as $index => $u)
                        @php
                            $rank = $users->firstItem() + $index;
                        @endphp
                        <tr class="hover:bg-[#FF0055]/5 transition {{ auth()->id() === $u->id ? 'bg-[#FF0055]/15 border-l-4 border-l-[#FF0055]' : '' }}">
                            <!-- Rank Number -->
                            <td class="py-4 px-6 text-center font-cyber font-bold text-base">
                                @if($rank === 1)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-400/20 text-amber-400 border border-amber-400/50 shadow-[0_0_15px_rgba(251,191,36,0.3)]">
                                        🥇
                                    </span>
                                @elseif($rank === 2)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-300/20 text-slate-300 border border-slate-300/50 shadow">
                                        🥈
                                    </span>
                                @elseif($rank === 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-700/20 text-amber-500 border border-amber-700/50 shadow">
                                        🥉
                                    </span>
                                @else
                                    <span class="text-neutral-500 font-mono">#{{ $rank }}</span>
                                @endif
                            </td>

                            <!-- Player Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('profile', ['id' => $u->id]) }}" class="shrink-0 group">
                                        <img src="{{ $u->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $u->name }}" class="w-10 h-10 rounded-xl bg-neutral-900 border border-[#FF0055]/40 group-hover:border-[#FF0055] group-hover:shadow-[0_0_10px_rgba(255,0,85,0.4)] transition" alt="Avatar">
                                    </a>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('profile', ['id' => $u->id]) }}" class="font-cyber font-bold text-white hover:text-[#FF2A6D] transition-colors {{ auth()->id() === $u->id ? 'text-[#FF2A6D]' : '' }}">
                                                {{ $u->name }}
                                            </a>
                                            @if($u->isAdmin())
                                                <span class="text-[9px] uppercase font-mono font-bold bg-[#FF0055] text-white px-1.5 py-0.5 rounded shadow">ADMIN</span>
                                            @endif
                                            @if(auth()->id() === $u->id)
                                                <span class="text-[9px] uppercase font-mono font-bold bg-[#FF0055] text-white px-1.5 py-0.5 rounded">ВЫ</span>
                                            @endif
                                            @if($u->is_bot)
                                                <span class="text-[9px] uppercase font-mono font-semibold bg-[#FF0055]/20 text-[#FF0055] border border-[#FF0055]/30 px-1.5 py-0.5 rounded">BOT</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-neutral-400 font-mono">
                                            {{ $u->steam_id ? 'Steam: ' . $u->steam_id : 'ID: ' . $u->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Level Circle Badge -->
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex w-8 h-8 rounded-xl items-center justify-center font-cyber font-bold text-sm bg-gradient-to-br from-[#FF0055] to-[#990033] text-white border border-[#FF0055]/50 shadow-[0_0_10px_rgba(255,0,85,0.4)]">
                                    {{ $u->level }}
                                </span>
                            </td>

                            <!-- Elo -->
                            <td class="py-4 px-6 text-right font-cyber font-bold text-base text-white">
                                {{ $u->elo }} <span class="text-[#FF0055] text-xs font-mono">ELO</span>
                            </td>

                            <!-- Matches count -->
                            <td class="py-4 px-6 text-right font-mono text-neutral-300">
                                {{ $u->matches_count }}
                                <span class="text-xs text-neutral-500">({{ $u->wins_count }}W / {{ $u->losses_count }}L)</span>
                            </td>

                            <!-- Winrate -->
                            <td class="py-4 px-6 text-right font-mono font-bold {{ $u->winrate >= 50 ? 'text-emerald-400' : 'text-neutral-400' }}">
                                {{ $u->winrate }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-neutral-500 font-mono text-sm">
                                Игроки не найдены
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#FF0055]/20 bg-[#08040A]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
