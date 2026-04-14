@php $d = $widget['data'] ?? []; @endphp
@if($goalEnabled)
<div class="obsidian-vote-card" id="vote-goal">
    <div class="obsidian-vote-header">
        <i class="bi bi-bullseye me-2"></i>
        <span data-field="title">{{ $d['title'] ?: trans('vote::messages.sections.goal') }}</span>
    </div>
    <div class="obsidian-vote-body">
        <div class="obsidian-vote-progress">
            <div class="obsidian-vote-progress-bar progress-bar" style="width: {{ min($goalPercentage, 100) }}%"></div>
            <span class="obsidian-vote-progress-text" id="goal-text">{{ $goalProgress }} / {{ $goalTarget }}</span>
        </div>
    </div>
</div>
@endif
