@php $d = $widget['data'] ?? []; @endphp
@if($displayRewards)
<div class="obsidian-vote-card">
    <div class="obsidian-vote-header">
        <i class="bi bi-gift-fill me-2"></i>
        <span data-field="title">{{ $d['title'] ?: trans('vote::messages.sections.rewards') }}</span>
    </div>
    <div class="obsidian-vote-body">
        <div class="d-flex flex-column gap-2">
            @foreach($rewards as $reward)
                <div class="obsidian-reward-row">
                    <div class="d-flex align-items-center gap-2">
                        @if($reward->image)
                            <img src="{{ $reward->imageUrl() }}" alt="{{ $reward->name }}" class="obsidian-reward-img">
                        @else
                            <div class="obsidian-reward-icon"><i class="bi bi-gift"></i></div>
                        @endif
                        <span>{{ $reward->name }}</span>
                    </div>
                    <span class="obsidian-reward-chance">{{ $reward->chances }}%</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
