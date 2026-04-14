@php
    $firstAmount = $tiers->first()->amount ?? 1;
    $pctToFirst = min(($currentAmount / $firstAmount) * 100, 100);
@endphp
<div class="lootlog mb-4">
    <div class="lootlog-head">
        <div class="lootlog-title">
            <i class="bi bi-trophy-fill"></i>
            <span data-field="title">{{ ($customTitle ?? '') ?: trans('rewardshowers::messages.this-month-loyalty') }}</span>
        </div>
        <div class="lootlog-xp">
            <span class="lootlog-xp-val">{{ $currentAmount }}</span>
            <span class="lootlog-xp-label">{{ trans('theme::messages.common.votes') }}</span>
        </div>
    </div>

    <div class="lootlog-body">
        {{-- Starting wire before first step --}}
        <div class="lootlog-start-wire">
            <div class="lootlog-start-wire-fill" style="height:{{ $pctToFirst }}%"></div>
        </div>

        @foreach($tiers as $tier)
            @php
                $completed = $currentAmount >= $tier->amount;
                $canClaim = $tier->canBeClaimByUser($loyalty, auth()->user());
                $pct = $tier->amount > 0 ? min(($currentAmount / $tier->amount) * 100, 100) : 0;
                $prevCompleted = $loop->first ? ($currentAmount >= 0) : ($currentAmount >= $tiers[$loop->index - 1]->amount);
                $desc = strip_tags($tier->description ?? '');
            @endphp
            <div class="lootlog-item @if($completed) --done @endif @if($canClaim) --claim @endif">
                {{-- Wire to next --}}
                @if(!$loop->last)
                    @php
                        $nextAmount = $tiers[$loop->index + 1]->amount;
                        $segPct = $completed ? 100 : ($prevCompleted ? min((($currentAmount - $tier->amount) / max($nextAmount - $tier->amount, 1)) * 100, 100) : 0);
                        $segPct = max(0, $segPct);
                    @endphp
                    <div class="lootlog-wire">
                        <div class="lootlog-wire-fill" style="height:{{ $segPct }}%"></div>
                    </div>
                @endif

                {{-- Node --}}
                <div class="lootlog-node"
                     @if($desc) data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $desc }}" @endif>
                    @if($completed)
                        <i class="bi bi-check-lg"></i>
                    @elseif($canClaim)
                        <i class="bi bi-gift-fill"></i>
                    @else
                        <span class="lootlog-node-num">{{ $tier->amount }}</span>
                    @endif
                </div>

                {{-- Card --}}
                <div class="lootlog-card">
                    <div class="lootlog-card-top">
                        <span class="lootlog-card-name">{{ $tier->name }}</span>
                        <span class="lootlog-card-req">{{ $tier->amount }} {{ trans('theme::messages.common.votes') }}</span>
                    </div>

                    @if($tier->description)
                        <div class="lootlog-card-desc">{!! $tier->description !!}</div>
                    @endif


                    @if($canClaim)
                        <form action="{{ route('rewardshowers.vote_steps.claim') }}" method="post" class="lootlog-claim">
                            @csrf
                            <input type="hidden" name="loyalty" value="{{ $loyalty->id }}">
                            <input type="hidden" name="tier" value="{{ $tier->id }}">
                            <button type="submit"><i class="bi bi-gift-fill me-1"></i> {{ trans('theme::messages.rewards.claim') }}</button>
                        </form>
                    @endif

                    @if($completed && !$canClaim)
                        <div class="lootlog-card-done"><i class="bi bi-patch-check-fill me-1"></i> {{ trans('theme::messages.rewards.unlocked') }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
.lootlog {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    overflow: hidden;
}
.lootlog-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.25rem;
    background: linear-gradient(90deg, rgba(var(--obsidian-primary-rgb),.08), transparent 70%);
    border-bottom: 1px solid var(--obsidian-border);
}
.lootlog-title {
    font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .95rem;
    text-transform: uppercase; letter-spacing: .05em; color: #fff;
    display: flex; align-items: center; gap: .5rem;
}
.lootlog-title i { color: var(--obsidian-primary); }
.lootlog-xp { display: flex; align-items: baseline; gap: .3rem; }
.lootlog-xp-val { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 1.2rem; color: var(--obsidian-primary); }
.lootlog-xp-label { font-family: 'Rajdhani', sans-serif; font-weight: 600; font-size: .75rem; text-transform: uppercase; color: var(--obsidian-text-dim); }

.lootlog-body {
    padding: 1.25rem;
    position: relative;
    /* Offset for the starting wire */
    padding-top: 2.5rem;
}

/* Starting wire (from top to first node) */
.lootlog-start-wire {
    position: absolute;
    left: calc(1.25rem + 19px); /* padding + half of 40px node */
    top: 1.25rem;
    height: 1.25rem;
    width: 2px;
    background: var(--obsidian-border);
    overflow: hidden;
    z-index: 0;
}
.lootlog-start-wire-fill {
    width: 100%;
    background: var(--obsidian-primary);
    box-shadow: 0 0 8px rgba(var(--obsidian-primary-rgb),.4);
    transition: height .8s cubic-bezier(.4,0,.2,1);
}

/* Item — grid: node | card */
.lootlog-item {
    display: grid;
    grid-template-columns: 40px 1fr;
    gap: 0 1rem;
    position: relative;
    padding-bottom: 1rem;
    align-items: start;
}
.lootlog-item:last-child { padding-bottom: 0; }

/* Wire — fixed position since align-items: start keeps node at top */
.lootlog-wire {
    position: absolute;
    left: 19px; /* center of 40px node */
    top: 44px; /* just below 40px node + 4px gap */
    bottom: 0;
    width: 2px;
    background: var(--obsidian-border);
    overflow: hidden;
    z-index: 0;
}
.lootlog-wire-fill {
    width: 100%;
    background: var(--obsidian-primary);
    box-shadow: 0 0 8px rgba(var(--obsidian-primary-rgb),.4);
    transition: height .8s cubic-bezier(.4,0,.2,1);
}

/* Node */
.lootlog-node {
    grid-column: 1;
    width: 40px; height: 40px;
    border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    z-index: 1;
    background: var(--obsidian-surface-2);
    border: 2px solid var(--obsidian-border);
    color: var(--obsidian-text-dim);
    font-size: .8rem;
    transition: all .35s cubic-bezier(.4,0,.2,1);
    cursor: default;
}
.lootlog-node-num { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .7rem; }

.lootlog-item.--done .lootlog-node {
    background: rgba(var(--obsidian-primary-rgb),.15);
    border-color: var(--obsidian-primary);
    color: var(--obsidian-primary);
    box-shadow: 0 0 12px rgba(var(--obsidian-primary-rgb),.3), inset 0 0 8px rgba(var(--obsidian-primary-rgb),.1);
}
.lootlog-item.--claim .lootlog-node {
    border-color: #fbbf24; color: #fbbf24;
    background: rgba(251,191,36,.08);
    animation: lootPulse 2.5s ease-in-out infinite;
}
@keyframes lootPulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(251,191,36,.2), 0 0 8px rgba(251,191,36,.15); }
    50% { box-shadow: 0 0 0 6px rgba(251,191,36,0), 0 0 16px rgba(251,191,36,.25); }
}

/* Card */
.lootlog-card {
    grid-column: 2;
    background: var(--obsidian-surface-2);
    border: 1px solid var(--obsidian-border);
    border-radius: .5rem;
    padding: .65rem .85rem;
    transition: border-color .3s, box-shadow .3s;
    position: relative; overflow: hidden;
}
.lootlog-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--obsidian-border); transition: background .3s, box-shadow .3s;
}
.lootlog-item.--done .lootlog-card::before {
    background: var(--obsidian-primary); box-shadow: 0 0 8px rgba(var(--obsidian-primary-rgb),.4);
}
.lootlog-item.--claim .lootlog-card { border-color: rgba(251,191,36,.2); }
.lootlog-item.--claim .lootlog-card::before { background: #fbbf24; box-shadow: 0 0 8px rgba(251,191,36,.3); }

.lootlog-card-top { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
.lootlog-card-name {
    font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .85rem;
    text-transform: uppercase; letter-spacing: .03em; color: #fff;
}
.lootlog-item.--done .lootlog-card-name { color: var(--obsidian-text); }

.lootlog-card-req {
    font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .7rem;
    color: var(--obsidian-primary); flex-shrink: 0;
    padding: .1rem .45rem; background: rgba(var(--obsidian-primary-rgb),.08); border-radius: .2rem;
}
.lootlog-item.--done .lootlog-card-req { color: var(--obsidian-text-dim); background: rgba(255,255,255,.03); }

.lootlog-card-desc { font-size: .78rem; color: var(--obsidian-text-dim); margin-top: .25rem; line-height: 1.4; }
.lootlog-card-desc p { margin: 0; }

/* Progress */
.lootlog-prog {
    position: relative; height: 20px; background: var(--obsidian-dark);
    border-radius: .3rem; overflow: hidden; margin-top: .5rem; border: 1px solid var(--obsidian-border);
}
.lootlog-prog-fill {
    position: absolute; top: 0; left: 0; bottom: 0;
    background: linear-gradient(90deg, var(--obsidian-primary), var(--obsidian-accent));
    border-radius: .3rem; transition: width .8s cubic-bezier(.4,0,.2,1);
}
.lootlog-prog-txt {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .7rem; color: #fff; z-index: 1;
    text-shadow: 0 1px 3px rgba(0,0,0,.5);
}

/* Done */
.lootlog-card-done {
    margin-top: .35rem; font-family: 'Rajdhani', sans-serif;
    font-weight: 600; font-size: .75rem; color: var(--obsidian-primary); opacity: .7;
}

/* Claim */
.lootlog-claim { margin-top: .5rem; }
.lootlog-claim button {
    width: 100%; padding: .45rem; font-family: 'Rajdhani', sans-serif;
    font-weight: 800; font-size: .75rem; letter-spacing: .08em; text-transform: uppercase;
    border: none; border-radius: .35rem; cursor: pointer; color: #0a0a0f;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    box-shadow: 0 2px 12px rgba(251,191,36,.25);
    transition: transform .2s, box-shadow .2s; position: relative; overflow: hidden;
}
.lootlog-claim button::after {
    content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
    animation: claimShine 2.5s ease-in-out infinite;
}
@keyframes claimShine { 0%{left:-50%} 40%{left:120%} 100%{left:120%} }
.lootlog-claim button:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(251,191,36,.35); }

/* Tooltip */
.lootlog .tooltip-inner {
    background: var(--obsidian-surface); border: 1px solid var(--obsidian-border);
    color: var(--obsidian-text); font-size: .8rem; max-width: 220px;
    padding: .5rem .75rem; border-radius: .5rem; box-shadow: 0 8px 24px rgba(0,0,0,.4);
}
.lootlog .tooltip-arrow::before { border-left-color: var(--obsidian-surface); }
</style>
@endpush
