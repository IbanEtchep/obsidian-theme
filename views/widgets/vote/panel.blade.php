@php $d = $widget['data'] ?? []; @endphp
<div class="obsidian-vote-card" id="vote-card">
    <div class="obsidian-vote-header">
        <i class="bi bi-star-fill me-2"></i>
        <span data-field="title">{{ $d['title'] ?: trans('vote::messages.sections.vote') }}</span>
    </div>
    <div class="obsidian-vote-body">
        <div class="spinner-parent h-100">
            <div class="spinner-border" style="color:var(--obsidian-primary)" role="status"></div>
        </div>

        <div class="@auth d-none @endauth" data-vote-step="1">
            @if(!$authRequired)
                <form class="d-flex gap-2" action="{{ route('vote.verify-user', '/') }}" id="voteNameForm">
                    <input type="text" id="stepNameInput" name="name" class="form-control"
                           value="{{ $name }}" placeholder="{{ trans('messages.fields.name') }}" required>
                    <button type="submit" class="btn btn-primary flex-shrink-0">
                        <i class="bi bi-arrow-right"></i>
                        <span class="d-none spinner-border spinner-border-sm load-spinner" role="status"></span>
                    </button>
                </form>
            @else
                <div class="text-center">
                    <p class="mb-3" style="color:var(--obsidian-text-dim)">{{ trans('vote::messages.errors.auth') }}</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>{{ trans('auth.login') }}
                    </a>
                </div>
                {{-- Hidden form for vote.js compatibility --}}
                <form class="d-none" action="{{ route('vote.verify-user', '/') }}" id="voteNameForm">
                    <span class="load-spinner d-none"></span>
                </form>
            @endif
        </div>

        <div class="@guest d-none @endguest h-100" data-vote-step="2">
            @auth
                <div class="text-center mb-3">
                    <span class="obsidian-vote-count">{{ $userVotes }}</span>
                    <span style="color:var(--obsidian-text-dim)">{{ trans_choice('vote::messages.votes', $userVotes) }}</span>
                </div>
            @endauth
            <div class="d-flex flex-column gap-2">
                @forelse($sites as $site)
                    <a class="obsidian-vote-site-btn" href="{{ $site->url }}" target="_blank" rel="noopener noreferrer"
                       data-vote-id="{{ $site->id }}" data-vote-url="{{ route('vote.vote', $site) }}"
                       @auth data-vote-time="{{ $site->getNextVoteTime($user, $request)?->valueOf() }}" @endauth>
                        <span class="d-flex align-items-center gap-2"><i class="bi bi-box-arrow-up-right"></i>{{ $site->name }}</span>
                        <span class="badge vote-timer"></span>
                    </a>
                @empty
                    <div class="alert alert-warning mb-0">{{ trans('vote::messages.errors.site') }}</div>
                @endforelse
            </div>
        </div>

        <div class="d-none text-center" data-vote-step="3"><p id="vote-result"></p></div>
        <div class="d-none" data-vote-step="server"><p>{{ trans('vote::messages.server') }}</p><div id="server-select"></div></div>
    </div>
</div>
