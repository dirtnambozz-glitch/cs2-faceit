<div class="space-y-8">
    <!-- Leaderboard Header & Filters -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-4 border-b border-[#1F273B]">
        <div>
            <div class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-[#FF5500] mb-1.5">
                <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                <span>SEASON 1 // ELO PRO LADDER</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-faceit font-extrabold text-white uppercase tracking-wider">
                ТАБЛИЦА ЛИДЕРОВ <span class="text-[#FF5500]">CS2</span>
            </h1>
            <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                Глобальный соревновательный рейтинг игроков платформы по системе ELO
            </p>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-64">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Поиск игрока или SteamID..." 
                    class="w-full bg-[#121622] border border-[#1F273B] rounded-xl pl-9 pr-4 py-2.5 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-[#FF5500] font-sans">
                <svg class="w-4 h-4 text-neutral-500 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select 
                wire:model.live="levelFilter" 
                class="w-full sm:w-auto bg-[#121622] border border-[#1F273B] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#FF5500] font-mono cursor-pointer">
                <option value="all">Все уровни (1-10)</option>
                @for($lvl = 10; $lvl >= 1; $lvl--)
                    <option value="{{ $lvl }}">Tier {{ $lvl }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- TOP 3 CHAMPIONS PODIUM (shown on page 1 with all filters) -->
    @if($users->currentPage() === 1 && empty($search) && $levelFilter === 'all' && $users->count() >= 3)
        @php
            $top1 = $users[0];
            $top2 = $users[1];
            $top3 = $users[2];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 items-end">
            <!-- 2ND PLACE (SILVER) -->
            <div class="bg-[#121622] border border-slate-400/30 rounded-2xl p-5 text-center relative overflow-hidden order-2 md:order-1 shadow-lg group hover:border-slate-300 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-slate-300"></div>
                <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#0E121B] border border-slate-400/40 text-xs font-mono font-bold text-slate-200 mb-3">
                    <span>🥈</span>
                    <span>2ND PLACE</span>
                </div>
                <div class="relative w-20 h-20 mx-auto mb-3">
                    <img src="{{ $top2->avatar_url }}" class="w-full h-full rounded-2xl border-2 border-slate-300/60 object-cover bg-[#0A0D14]" alt="{{ $top2->name }}">
                    <div class="absolute -bottom-2 -right-2">
                        <x-faceit-level :level="$top2->level" size="sm" />
                    </div>
                </div>
                <a href="{{ route('profile', ['id' => $top2->id]) }}" class="font-faceit font-extrabold text-xl text-white hover:text-[#FF5500] transition block truncate">
                    {{ $top2->name }}
                </a>
                <div class="text-xs font-mono text-slate-300 mt-1">
                    <strong class="text-lg font-bold text-white">{{ $top2->elo }}</strong> <span class="text-[#FF5500]">ELO</span>
                </div>
                <div class="mt-3 pt-3 border-t border-[#1F273B] text-[11px] font-mono text-neutral-400 flex items-center justify-around">
                    <span>{{ $top2->winrate }}% WR</span>
                    <span>•</span>
                    <span>{{ $top2->matches_count }} Матчей</span>
                </div>
            </div>

            <!-- 1ST PLACE (GOLD CHAMPION) -->
            <div class="bg-[#151B2A] border-2 border-amber-400/60 rounded-2xl p-6 text-center relative overflow-hidden order-1 md:order-2 shadow-[0_0_35px_rgba(251,191,36,0.15)] group hover:border-amber-400 transition scale-100 md:scale-105 z-10">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-500"></div>
                <div class="inline-flex items-center space-x-1.5 px-4 py-1 rounded-full bg-[#0E121B] border border-amber-400/60 text-xs font-mono font-bold text-amber-300 mb-3 shadow-[0_0_15px_rgba(251,191,36,0.25)]">
                    <span>👑</span>
                    <span>1ST PLACE • LEADER</span>
                </div>
                <div class="relative w-24 h-24 mx-auto mb-3">
                    <img src="{{ $top1->avatar_url }}" class="w-full h-full rounded-2xl border-2 border-amber-400 object-cover bg-[#0A0D14] shadow-[0_0_20px_rgba(251,191,36,0.3)]" alt="{{ $top1->name }}">
                    <div class="absolute -bottom-2 -right-2">
                        <x-faceit-level :level="$top1->level" size="md" />
                    </div>
                </div>
                <a href="{{ route('profile', ['id' => $top1->id]) }}" class="font-faceit font-black text-2xl text-white hover:text-amber-400 transition block truncate">
                    {{ $top1->name }}
                </a>
                <div class="text-sm font-mono text-amber-300 mt-1">
                    <strong class="text-2xl font-bold text-white">{{ $top1->elo }}</strong> <span class="text-[#FF5500] font-bold">ELO</span>
                </div>
                <div class="mt-3 pt-3 border-t border-[#1F273B] text-xs font-mono text-neutral-300 flex items-center justify-around">
                    <span class="text-emerald-400 font-bold">{{ $top1->winrate }}% WR</span>
                    <span>•</span>
                    <span>{{ $top1->wins_count }}W / {{ $top1->losses_count }}L</span>
                </div>
            </div>

            <!-- 3RD PLACE (BRONZE) -->
            <div class="bg-[#121622] border border-amber-700/40 rounded-2xl p-5 text-center relative overflow-hidden order-3 md:order-3 shadow-lg group hover:border-amber-600 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-amber-600"></div>
                <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#0E121B] border border-amber-700/50 text-xs font-mono font-bold text-amber-500 mb-3">
                    <span>🥉</span>
                    <span>3RD PLACE</span>
                </div>
                <div class="relative w-20 h-20 mx-auto mb-3">
                    <img src="{{ $top3->avatar_url }}" class="w-full h-full rounded-2xl border-2 border-amber-600/60 object-cover bg-[#0A0D14]" alt="{{ $top3->name }}">
                    <div class="absolute -bottom-2 -right-2">
                        <x-faceit-level :level="$top3->level" size="sm" />
                    </div>
                </div>
                <a href="{{ route('profile', ['id' => $top3->id]) }}" class="font-faceit font-extrabold text-xl text-white hover:text-[#FF5500] transition block truncate">
                    {{ $top3->name }}
                </a>
                <div class="text-xs font-mono text-amber-400 mt-1">
                    <strong class="text-lg font-bold text-white">{{ $top3->elo }}</strong> <span class="text-[#FF5500]">ELO</span>
                </div>
                <div class="mt-3 pt-3 border-t border-[#1F273B] text-[11px] font-mono text-neutral-400 flex items-center justify-around">
                    <span>{{ $top3->winrate }}% WR</span>
                    <span>•</span>
                    <span>{{ $top3->matches_count }} Матчей</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Leaderboard Table Card -->
    <div class="bg-[#121622] border border-[#1F273B] rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#1F273B] bg-[#0E121B] text-[11px] font-mono font-bold uppercase tracking-wider text-neutral-400">
                        <th class="py-3.5 px-6 text-center w-20">Ранг</th>
                        <th class="py-3.5 px-6">Игрок</th>
                        <th class="py-3.5 px-6 text-center w-28">Уровень</th>
                        <th class="py-3.5 px-6 text-right">Рейтинг ELO</th>
                        <th class="py-3.5 px-6 text-right">Матчей</th>
                        <th class="py-3.5 px-6 text-right">Винрейт</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1F273B] text-sm">
                    @forelse($users as $index => $u)
                        @php
                            $rank = $users->firstItem() + $index;
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition {{ auth()->id() === $u->id ? 'bg-[#FF5500]/5 border-l-4 border-l-[#FF5500]' : '' }}">
                            <!-- Rank Number -->
                            <td class="py-3.5 px-6 text-center font-mono font-bold text-sm">
                                @if($rank === 1)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-amber-400/10 text-amber-400 border border-amber-400/40 text-xs">
                                        🥇 #1
                                    </span>
                                @elseif($rank === 2)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-slate-300/10 text-slate-300 border border-slate-300/40 text-xs">
                                        🥈 #2
                                    </span>
                                @elseif($rank === 3)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-amber-700/10 text-amber-500 border border-amber-700/40 text-xs">
                                        🥉 #3
                                    </span>
                                @else
                                    <span class="text-neutral-500">#{{ $rank }}</span>
                                @endif
                            </td>

                            <!-- Player Info -->
                            <td class="py-3.5 px-6">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('profile', ['id' => $u->id]) }}" class="shrink-0 group">
                                        <img src="{{ $u->avatar_url }}" class="w-9 h-9 rounded-lg bg-[#0A0D14] border border-[#1F273B] group-hover:border-[#FF5500]/60 transition object-cover" alt="{{ $u->name }}">
                                    </a>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('profile', ['id' => $u->id]) }}" class="font-faceit font-bold text-base text-white hover:text-[#FF5500] transition-colors {{ auth()->id() === $u->id ? 'text-[#FF5500]' : '' }}">
                                                {{ $u->name }}
                                            </a>
                                            @if($u->isAdmin())
                                                <span class="text-[9px] uppercase font-mono font-bold bg-[#FF5500] text-white px-1.5 py-0.2 rounded">ADMIN</span>
                                            @endif
                                            @if(auth()->id() === $u->id)
                                                <span class="text-[9px] uppercase font-mono font-bold bg-emerald-500 text-neutral-950 px-1.5 py-0.2 rounded font-bold">ВЫ</span>
                                            @endif
                                            @if($u->is_bot)
                                                <span class="text-[9px] uppercase font-mono font-semibold bg-neutral-800 text-neutral-400 border border-neutral-700 px-1.5 py-0.2 rounded">BOT</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-neutral-400 font-mono">
                                            {{ $u->steam_id ? 'Steam: ' . $u->steam_id : 'ID: ' . $u->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Level Shield Badge -->
                            <td class="py-3.5 px-6 text-center">
                                <x-faceit-level :level="$u->level" size="sm" />
                            </td>

                            <!-- Elo -->
                            <td class="py-3.5 px-6 text-right font-mono font-bold text-sm text-white">
                                {{ $u->elo }} <span class="text-[#FF5500] text-xs font-mono">ELO</span>
                            </td>

                            <!-- Matches count -->
                            <td class="py-3.5 px-6 text-right font-mono text-neutral-300 text-xs">
                                <span class="text-white font-bold">{{ $u->matches_count }}</span>
                                <span class="text-neutral-500">({{ $u->wins_count }}W / {{ $u->losses_count }}L)</span>
                            </td>

                            <!-- Winrate -->
                            <td class="py-3.5 px-6 text-right font-mono font-bold {{ $u->winrate >= 50 ? 'text-emerald-400' : 'text-neutral-400' }}">
                                {{ $u->winrate }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-neutral-500 font-mono text-sm">
                                Игроки не найдены
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#1F273B] bg-[#0E121B]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
