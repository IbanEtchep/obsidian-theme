@php
    $d = $widget['data'] ?? [];
    $joinTitle = $d['title'] ?: trans('theme::messages.join.title');
    $step1 = $d['step_1'] ?: trans('theme::messages.join.step_1');
    $step2 = $d['step_2'] ?: trans('theme::messages.join.step_2');
    $step3 = $d['step_3'] ?: trans('theme::messages.join.step_3');
    $serverIp = $d['server_ip'] ?: ($server ? $server->fullAddress() : 'play.example.com');
@endphp
<section class="obsidian-section obsidian-section-alt py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <div class="obsidian-section-divider mx-auto mb-3"></div>
            <h2 class="obsidian-reveal" data-field="title">{{ $joinTitle }}</h2>
        </div>

        <div class="row g-4 align-items-center">
            {{-- Steps --}}
            <div class="col-lg-6">
                <div class="d-flex flex-column gap-4">
                    @foreach([1 => $step1, 2 => $step2, 3 => $step3] as $num => $text)
                        <div class="d-flex align-items-start gap-3 obsidian-reveal">
                            <div class="obsidian-step-number">{{ $num }}</div>
                            <div>
                                <p class="mb-0" style="color:var(--obsidian-text)" data-field="step_{{ $num }}">{{ $text }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- IP Copy box --}}
            <div class="col-lg-6">
                <div class="obsidian-ip-box obsidian-reveal">
                    <div class="obsidian-ip-label">Adresse du serveur</div>
                    <div class="obsidian-ip-row">
                        <code class="obsidian-ip-value" id="obsidian-server-ip">{{ $serverIp }}</code>
                        <button type="button" class="obsidian-ip-copy" onclick="obsidianCopyIp()" title="Copier">
                            <i class="bi bi-clipboard" id="obsidian-copy-icon"></i>
                        </button>
                    </div>
                    <div class="obsidian-ip-copied d-none" id="obsidian-ip-copied">
                        <i class="bi bi-check-circle-fill me-1"></i> Copié !
                    </div>
                </div>

                @if($server && $server->join_url)
                    <div class="text-center mt-3 obsidian-reveal">
                        <a href="{{ $server->join_url }}" class="btn btn-primary btn-lg obsidian-btn-glow">
                            <i class="bi bi-play-fill me-1"></i> Rejoindre
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.obsidian-step-number {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: .625rem;
    background: rgba(var(--obsidian-primary-rgb),.1);
    border: 1px solid rgba(var(--obsidian-primary-rgb),.2);
    color: var(--obsidian-primary);
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 1.1rem;
}

.obsidian-ip-box {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    padding: 1.5rem;
    text-align: center;
}

.obsidian-ip-label {
    font-family: 'Rajdhani', sans-serif;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-size: .75rem;
    font-weight: 600;
    color: var(--obsidian-text-dim);
    margin-bottom: .75rem;
}

.obsidian-ip-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .75rem;
    background: var(--obsidian-dark);
    border: 1px solid rgba(var(--obsidian-primary-rgb),.15);
    border-radius: .5rem;
    padding: .75rem 1rem;
}

.obsidian-ip-value {
    font-family: 'Rajdhani', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: .06em;
    color: var(--obsidian-primary);
    background: none;
    user-select: all;
}

.obsidian-ip-copy {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: .5rem;
    border: 1px solid rgba(var(--obsidian-primary-rgb),.2);
    background: rgba(var(--obsidian-primary-rgb),.08);
    color: var(--obsidian-primary);
    font-size: 1.1rem;
    cursor: pointer;
    transition: all .2s;
}
.obsidian-ip-copy:hover {
    background: var(--obsidian-primary);
    color: var(--obsidian-primary-contrast);
    box-shadow: 0 0 16px rgba(var(--obsidian-primary-rgb),.3);
}

.obsidian-ip-copied {
    margin-top: .75rem;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: .85rem;
    color: #22c55e;
    letter-spacing: .04em;
}
</style>

<script>
function obsidianCopyIp() {
    var ip = document.getElementById('obsidian-server-ip').textContent.trim();
    navigator.clipboard.writeText(ip).then(function() {
        var icon = document.getElementById('obsidian-copy-icon');
        var msg = document.getElementById('obsidian-ip-copied');
        icon.className = 'bi bi-check-lg';
        msg.classList.remove('d-none');
        setTimeout(function() {
            icon.className = 'bi bi-clipboard';
            msg.classList.add('d-none');
        }, 2000);
    });
}
</script>
