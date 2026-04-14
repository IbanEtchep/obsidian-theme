@php $d = $widget['data'] ?? []; @endphp
@plugin('rewardshowers')
    @if(auth()->check())
        @php
            $loyalty = \Azuriom\Plugin\RewardShowers\Models\VoteLoyalty::firstOrCreate([
                'user_id' => auth()->id(), 'month' => now()->startOfMonth(),
            ]);
            $tiers = \Azuriom\Plugin\RewardShowers\Models\VoteStep::orderBy('amount')->get();
            $currentAmount = $loyalty->amount;
            $maxAmount = $tiers->max('amount') ?: 1;
        @endphp
        @if($tiers->count() > 0)
            @include('plugins.rewardshowers.steps-horizontal', [
                'loyalty' => $loyalty, 'tiers' => $tiers,
                'currentAmount' => $currentAmount, 'maxAmount' => $maxAmount,
                'customTitle' => $d['title'] ?? '',
            ])
        @endif
    @endif
@endplugin
