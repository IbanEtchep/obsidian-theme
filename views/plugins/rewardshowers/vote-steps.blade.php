@php
    $loyalty = \Azuriom\Plugin\RewardShowers\Models\VoteLoyalty::firstOrCreate([
        'user_id' => auth()->id(),
        'month' => now()->startOfMonth(),
    ]);
    $tiers = \Azuriom\Plugin\RewardShowers\Models\VoteStep::orderBy('amount')->get();
    $totalTiers = $tiers->count();
    $maxAmount = $tiers->max('amount') ?: 1;
    $currentAmount = $loyalty->amount;
    $variant = theme_config('vote.steps_variant') ?? 'horizontal';
@endphp

@if($totalTiers > 0)
    @if($variant === 'vertical')
        @include('plugins.rewardshowers.steps-vertical', compact('loyalty', 'tiers', 'currentAmount', 'maxAmount'))
    @else
        @include('plugins.rewardshowers.steps-horizontal', compact('loyalty', 'tiers', 'currentAmount', 'maxAmount'))
    @endif
@endif
