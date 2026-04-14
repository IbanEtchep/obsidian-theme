@php $d = $widget['data'] ?? []; @endphp
<div class="obsidian-vote-card">
    <div class="obsidian-vote-header">
        <i class="bi bi-trophy-fill me-2"></i>
        <span data-field="title">{{ $d['title'] ?: trans('vote::messages.sections.top') }}</span>
    </div>
    <div class="obsidian-vote-body p-0">
        @foreach($votes as $id => $vote)
            <div class="obsidian-vote-rank @if($loop->index < 3) obsidian-vote-rank-top @endif">
                <div class="obsidian-vote-rank-pos">
                    @if($loop->index === 0)
                        <i class="bi bi-trophy-fill" style="color:#fbbf24"></i>
                    @elseif($loop->index === 1)
                        <i class="bi bi-trophy-fill" style="color:#94a3b8"></i>
                    @elseif($loop->index === 2)
                        <i class="bi bi-trophy-fill" style="color:#c2884d"></i>
                    @else
                        #{{ $id }}
                    @endif
                </div>
                <img src="{{ $vote->user->getAvatar() }}" alt="{{ $vote->user->name }}" class="obsidian-vote-rank-avatar">
                <span class="obsidian-vote-rank-name">{{ $vote->user->name }}</span>
                <span class="obsidian-vote-rank-votes">{{ $vote->votes }}</span>
            </div>
        @endforeach
        @if($votes->isEmpty())
            <div class="p-4 text-center" style="color:var(--obsidian-text-dim)">{{ trans('theme::messages.common.no_votes_yet') }}</div>
        @endif
    </div>
</div>
