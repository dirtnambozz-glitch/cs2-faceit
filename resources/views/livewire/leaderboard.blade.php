<div>
    <!-- Leaderboard Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-[#FF5500] mb-1">
                <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                <span>GLOBAL RANKINGS // ELO LADDER</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-faceit font-extrabold text-white uppercase tracking-wider">
                ТАБЛИЦА ЛИДЕРОВ <span class="text-[#FF5500]">CS2</span>
            </h1>
            <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                Рейтинг сильнейших игроков платформы по соревновательной системе пересчета ELO
            </p>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-64">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Поиск игрока..." 
                    class="w-full bg-[#121620] border border-[#22293A] rounded-xl pl-9 pr-4 py-2.5 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-[#FF5500] font-sans">
                <svg class="w-4 h-4 text-neutral-500 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select 
                wire:model.live="levelFilter" 
                class="w-full sm:w-auto bg-[#121620] border border-[#22293A] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#FF5500] font-mono cursor-pointer">
                <option value="all">Все уровни (1-10)</option>
                @for($lvl = 10; $lvl >= 1; $lvl--)
                    <option value="{{ $lvl }}">Уровень {{ $lvl }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Leaderboard Table Card -->
    <div class="bg-[#121620] border border-[#22293A] rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#22293A] bg-[#0C0E14] text-[11px] font-mono font-bold uppercase tracking-wider text-neutral-400">
                        <th class="py-3.5 px-6 text-center w-20">Ранг</th>
                        <th class="py-3.5 px-6">Игрок</th>
                        <th class="py-3.5 px-6 text-center w-28">Уровень</th>
                        <th class="py-3.5 px-6 text-right">Рейтинг ELO</th>
                        <th class="py-3.5 px-6 text-right">Матчей</th>
                        <th class="py-3.5 px-6 text-right">Винрейт</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#22293A] text-sm">
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
                                        <img src="{{ $u->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $u->name }}" class="w-9 h-9 rounded-lg bg-neutral-900 border border-white/10 group-hover:border-[#FF5500]/60 transition object-cover" alt="Avatar">
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
            <div class="p-4 border-t border-[#22293A] bg-[#0C0E14]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
