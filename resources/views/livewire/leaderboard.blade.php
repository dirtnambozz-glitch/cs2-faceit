<div class="space-y-6">
    <!-- Leaderboard Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-[#FF5500] mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span>
                <span>GLOBAL RANKINGS // ELO SYSTEM</span>
            </div>
            <h1 class="text-3xl font-heading font-extrabold text-white tracking-tight">
                Таблица Лидеров
            </h1>
            <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                Рейтинг сильнейших игроков CS2 по соревновательной системе пересчета очков Elo
            </p>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-2.5">
            <div class="relative w-full sm:w-64">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Поиск по никнейму..." 
                    class="w-full bg-[#12151B] border border-white/[0.08] focus:border-[#FF5500] rounded-xl pl-9 pr-3.5 py-2 text-xs text-white placeholder-neutral-500 focus:outline-none font-mono transition-colors">
                <svg class="w-3.5 h-3.5 text-neutral-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select 
                wire:model.live="levelFilter" 
                class="w-full sm:w-auto bg-[#12151B] border border-white/[0.08] focus:border-[#FF5500] rounded-xl px-3 py-2 text-xs text-white focus:outline-none font-mono transition-colors cursor-pointer">
                <option value="all">Все уровни (1–10)</option>
                @for($lvl = 10; $lvl >= 1; $lvl--)
                    <option value="{{ $lvl }}">Уровень {{ $lvl }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Leaderboard Table Container -->
    <div class="bg-[#12151B] border border-white/[0.07] rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/[0.06] bg-[#07090D] text-[11px] font-mono font-semibold uppercase tracking-wider text-neutral-400">
                        <th class="py-3 px-5 text-center w-20">Ранг</th>
                        <th class="py-3 px-5">Игрок</th>
                        <th class="py-3 px-5 text-center w-24">Уровень</th>
                        <th class="py-3 px-5 text-right">Рейтинг Elo</th>
                        <th class="py-3 px-5 text-right">Матчей (W / L)</th>
                        <th class="py-3 px-5 text-right">Винрейт</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04] text-sm">
                    @forelse($users as $index => $u)
                        @php
                            $rank = $users->firstItem() + $index;
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors {{ auth()->id() === $u->id ? 'bg-[#FF5500]/[0.06] border-l-2 border-l-[#FF5500]' : '' }}">
                            <!-- Rank Number -->
                            <td class="py-3.5 px-5 text-center font-mono">
                                @if($rank === 1)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-400/10 text-amber-300 border border-amber-400/30 font-bold text-xs">
                                        #1
                                    </span>
                                @elseif($rank === 2)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-300/10 text-slate-300 border border-slate-300/30 font-bold text-xs">
                                        #2
                                    </span>
                                @elseif($rank === 3)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-700/10 text-amber-500 border border-amber-700/30 font-bold text-xs">
                                        #3
                                    </span>
                                @else
                                    <span class="text-neutral-500 font-mono text-xs tabular-nums">#{{ $rank }}</span>
                                @endif
                            </td>

                            <!-- Player Info -->
                            <td class="py-3.5 px-5">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('profile', ['id' => $u->id]) }}" class="shrink-0 group">
                                        <img src="{{ $u->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $u->name }}" class="w-9 h-9 rounded-xl bg-neutral-900 border border-white/[0.1] group-hover:border-[#FF5500] transition object-cover" alt="Avatar">
                                    </a>
                                    <div>
                                        <div class="flex items-center space-x-1.5">
                                            <a href="{{ route('profile', ['id' => $u->id]) }}" class="font-heading font-bold text-white hover:text-[#FF5500] transition-colors {{ auth()->id() === $u->id ? 'text-[#FF5500]' : '' }}">
                                                {{ $u->name }}
                                            </a>
                                            @if($u->isAdmin())
                                                <span class="text-[9px] uppercase font-mono font-bold bg-white/[0.08] text-white px-1.5 py-0.2 rounded">ADMIN</span>
                                            @endif
                                            @if(auth()->id() === $u->id)
                                                <span class="text-[9px] uppercase font-mono font-bold bg-[#FF5500] text-white px-1.5 py-0.2 rounded">ВЫ</span>
                                            @endif
                                            @if($u->is_bot)
                                                <span class="text-[9px] uppercase font-mono font-semibold bg-white/[0.05] text-neutral-400 border border-white/[0.1] px-1.5 py-0.2 rounded">BOT</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-neutral-500 font-mono">
                                            {{ $u->steam_id ? 'SteamID: ' . $u->steam_id : 'ID: ' . $u->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Level Shield Badge -->
                            <td class="py-3.5 px-5 text-center">
                                <span class="inline-flex w-7 h-7 rounded-lg items-center justify-center font-heading font-bold text-xs bg-gradient-to-br from-[#1E2638] to-[#111724] text-white border border-white/[0.1] shadow-sm">
                                    {{ $u->level }}
                                </span>
                            </td>

                            <!-- Elo -->
                            <td class="py-3.5 px-5 text-right font-mono font-bold text-white tabular-nums">
                                {{ $u->elo }} <span class="text-neutral-500 text-xs font-normal">ELO</span>
                            </td>

                            <!-- Matches count & W/L -->
                            <td class="py-3.5 px-5 text-right font-mono text-neutral-300 tabular-nums">
                                <span class="font-semibold">{{ $u->matches_count }}</span>
                                <span class="text-xs text-neutral-500 ml-1">({{ $u->wins_count }}W / {{ $u->losses_count }}L)</span>
                            </td>

                            <!-- Winrate -->
                            <td class="py-3.5 px-5 text-right font-mono font-semibold tabular-nums {{ $u->winrate >= 50 ? 'text-emerald-400' : 'text-neutral-400' }}">
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
            <div class="p-4 border-t border-white/[0.06] bg-[#07090D]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
