@php
    $totalTiers = $tiers->count();
    // Progress: how far through the tiers are we?
    // Find current tier index for fill calculation
    $completedCount = $tiers->filter(fn($t) => $currentAmount >= $t->amount)->count();
    $nextTier = $tiers->first(fn($t) => $currentAmount < $t->amount);
    $pctWithinNext = 0;
    if ($nextTier) {
        $prevAmount = $completedCount > 0 ? $tiers->values()[$completedCount - 1]->amount : 0;
        $pctWithinNext = ($nextTier->amount - $prevAmount) > 0
            ? ($currentAmount - $prevAmount) / ($nextTier->amount - $prevAmount)
            : 0;
    }
    // Fill = (completed tiers + partial) / total, as percentage
    $fillPct = $totalTiers > 0 ? (($completedCount + max(0, $pctWithinNext)) / $totalTiers) * 100 : 0;
    $fillPct = min($fillPct, 100);
@endphp
<div class="rtrack mb-4">
    <div class="rtrack-head">
        <div class="rtrack-title">
            <i class="bi bi-trophy-fill"></i>
            <span data-field="title">{{ ($customTitle ?? '') ?: trans('rewardshowers::messages.this-month-loyalty') }}</span>
        </div>
        <div class="rtrack-xp">
            <span class="rtrack-xp-current">{{ $currentAmount }}</span>
            <span class="rtrack-xp-sep">/</span>
            <span class="rtrack-xp-max">{{ $maxAmount }}</span>
        </div>
    </div>

    <div class="rtrack-rail">
        {{-- Bar --}}
        <div class="rtrack-bar">
            <div class="rtrack-bar-fill" style="width:{{ $fillPct }}%">
                <div class="rtrack-bar-shine"></div>
            </div>
        </div>

        {{-- Nodes — evenly spaced via flexbox --}}
        <div class="rtrack-nodes">
            @foreach($tiers as $tier)
                @php
                    $completed = $currentAmount >= $tier->amount;
                    $canClaim = $tier->canBeClaimByUser($loyalty, auth()->user());
                    $desc = strip_tags($tier->description ?? '');
                @endphp
                <div class="rtrack-node @if($completed) --done @endif @if($canClaim) --claim @endif">
                    <div class="rtrack-node-pip"
                         @if($desc) data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $desc }}" @endif>
                        @if($completed)
                            <i class="bi bi-check-lg"></i>
                        @elseif($canClaim)
                            <i class="bi bi-gift-fill"></i>
                        @else
                            <i class="bi bi-lock-fill"></i>
                        @endif
                    </div>
                    <div class="rtrack-node-tag">
                        <strong>{{ $tier->amount }}</strong>
                        <span>{{ $tier->name }}</span>
                    </div>
                    @if($canClaim)
                        <form action="{{ route('rewardshowers.vote_steps.claim') }}" method="post" class="rtrack-node-claim">
                            @csrf
                            <input type="hidden" name="loyalty" value="{{ $loyalty->id }}">
                            <input type="hidden" name="tier" value="{{ $tier->id }}">
                            <button type="submit"><i class="bi bi-gift-fill me-1"></i> {{ trans('theme::messages.rewards.claim') }}</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('styles')
<style>
.rtrack {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    overflow: hidden;
}
.rtrack-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.25rem;
    background: linear-gradient(90deg, rgba(var(--obsidian-primary-rgb),.08), transparent 70%);
    border-bottom: 1px solid var(--obsidian-border);
}
.rtrack-title {
    font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .95rem;
    text-transform: uppercase; letter-spacing: .05em; color: #fff;
    display: flex; align-items: center; gap: .5rem;
}
.rtrack-title i { color: var(--obsidian-primary); }
.rtrack-xp { font-family: 'Rajdhani', sans-serif; font-weight: 700; display: flex; align-items: baseline; gap: .15rem; }
.rtrack-xp-current { color: var(--obsidian-primary); font-size: 1.2rem; }
.rtrack-xp-sep { color: var(--obsidian-text-dim); font-size: .8rem; }
.rtrack-xp-max { color: var(--obsidian-text-dim); font-size: .85rem; }

/* Rail */
.rtrack-rail {
    position: relative;
    padding: 1.5rem 1.25rem 1rem;
}

/* Bar — behind the nodes, aligned to pip center (38px/2 = 19px from top of pip) */
.rtrack-bar {
    position: absolute;
    left: 1.25rem; right: 1.25rem;
    top: calc(1.5rem + 19px - 3px); /* padding-top + half of pip - half of bar height */
    height: 6px;
    background: var(--obsidian-surface-2);
    border-radius: 3px;
    border: 1px solid var(--obsidian-border);
    overflow: hidden;
    z-index: 0;
}
.rtrack-bar-fill {
    position: absolute; inset: -1px; right: auto;
    border-radius: 3px;
    background: var(--obsidian-primary);
    box-shadow: 0 0 10px rgba(var(--obsidian-primary-rgb),.4);
    transition: width 1s cubic-bezier(.4,0,.2,1);
    overflow: hidden;
}
.rtrack-bar-shine {
    position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
    animation: rtrackShine 3s ease-in-out infinite;
}
@keyframes rtrackShine { 0%{left:-60%} 50%{left:100%} 100%{left:100%} }

/* Nodes — evenly distributed with flexbox */
.rtrack-nodes {
    display: flex;
    justify-content: space-between;
    position: relative;
    z-index: 1;
}

.rtrack-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

/* Pip */
.rtrack-node-pip {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    background: var(--obsidian-dark);
    border: 3px solid var(--obsidian-surface-2);
    color: var(--obsidian-text-dim);
    transition: all .35s cubic-bezier(.4,0,.2,1);
    position: relative;
    cursor: default;
}
.rtrack-node-pip::after {
    content: ''; position: absolute; inset: -5px; border-radius: 50%;
    border: 2px solid transparent; transition: all .3s;
}

.rtrack-node.--done .rtrack-node-pip {
    background: var(--obsidian-primary); border-color: var(--obsidian-primary);
    color: #fff; box-shadow: 0 0 16px rgba(var(--obsidian-primary-rgb),.5);
}
.rtrack-node.--done .rtrack-node-pip::after { border-color: rgba(var(--obsidian-primary-rgb),.2); }

.rtrack-node.--claim .rtrack-node-pip {
    background: var(--obsidian-dark); border-color: #fbbf24; color: #fbbf24;
    animation: nodeGlow 2s ease-in-out infinite;
}
.rtrack-node.--claim .rtrack-node-pip::after {
    border-color: rgba(251,191,36,.2); animation: nodeRing 2s ease-in-out infinite;
}
@keyframes nodeGlow { 0%,100%{box-shadow:0 0 8px rgba(251,191,36,.3)} 50%{box-shadow:0 0 20px rgba(251,191,36,.5)} }
@keyframes nodeRing { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.15);opacity:.5} }

/* Tag */
.rtrack-node-tag {
    margin-top: .5rem; display: flex; flex-direction: column; align-items: center;
    line-height: 1.15; white-space: nowrap;
}
.rtrack-node-tag strong { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .8rem; color: var(--obsidian-text-dim); }
.rtrack-node.--done .rtrack-node-tag strong { color: var(--obsidian-primary); }
.rtrack-node.--claim .rtrack-node-tag strong { color: #fbbf24; }
.rtrack-node-tag span {
    font-family: 'Rajdhani', sans-serif; font-weight: 600; font-size: .72rem;
    text-transform: uppercase; letter-spacing: .05em; color: var(--obsidian-text);
    margin-top: .15rem;
}

/* Claim */
.rtrack-node-claim { margin-top: .35rem; }
.rtrack-node-claim button {
    padding: .25rem .7rem; font-family: 'Rajdhani', sans-serif; font-weight: 800;
    font-size: .65rem; letter-spacing: .08em; text-transform: uppercase;
    border: none; border-radius: .3rem; cursor: pointer; color: #0a0a0f;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    box-shadow: 0 2px 8px rgba(251,191,36,.3);
    transition: transform .2s, box-shadow .2s;
}
.rtrack-node-claim button:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 6px 20px rgba(251,191,36,.4); }

/* Tooltip styling */
.rtrack .tooltip-inner {
    background: var(--obsidian-surface); border: 1px solid var(--obsidian-border);
    color: var(--obsidian-text); font-size: .8rem; max-width: 220px;
    padding: .5rem .75rem; border-radius: .5rem;
    box-shadow: 0 8px 24px rgba(0,0,0,.4);
}
.rtrack .tooltip-arrow::before { border-top-color: var(--obsidian-surface); }

@media (max-width: 767.98px) {
    .rtrack-node-pip { width: 30px; height: 30px; font-size: .65rem; border-width: 2px; }
    .rtrack-node-pip::after { inset: -4px; }
    .rtrack-node-tag strong { font-size: .7rem; }
    .rtrack-node-tag span { display: none; }
    .rtrack-node-claim button { font-size: .55rem; padding: .2rem .5rem; }
}
</style>
@endpush
