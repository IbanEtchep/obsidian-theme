<footer class="obsidian-footer mt-auto" id="obsidian-footer">
    {{-- Top accent line --}}
    <div class="ft-accent"></div>

    <div class="container">
        {{-- Main content --}}
        <div class="ft-grid">
            {{-- Brand column --}}
            <div class="ft-brand">
                <div class="ft-logo">
                    @if(site_logo())
                        <img src="{{ site_logo() }}" alt="{{ site_name() }}">
                    @endif
                    <span class="ft-site-name">{{ site_name() }}</span>
                </div>
                <p class="ft-desc" data-footer-field="description">
                    {{ theme_config('footer.description') ?: trans('theme::messages.footer.description') }}
                </p>
                {{-- Social inline with brand --}}
                <div class="ft-socials">
                    @foreach(social_links() as $link)
                        <a href="{{ $link->value }}" title="{{ $link->title }}" target="_blank" rel="noopener noreferrer"
                           class="ft-social" data-bs-toggle="tooltip">
                            <i class="{{ $link->icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Links columns --}}
            <div class="ft-links">
                <h6 class="ft-heading" data-footer-field="links_title">
                    {{ theme_config('footer.links_title') ?: trans('theme::messages.footer.links') }}
                </h6>
                <ul id="obsidian-footer-links-display">
                    @php $footerLinks = theme_config('footer.links') ?? []; @endphp
                    @if(!empty($footerLinks))
                        @foreach($footerLinks as $link)
                            @if(!empty($link['name']) && !empty($link['url']))
                                <li><a href="{{ $link['url'] }}">{{ $link['name'] }}</a></li>
                            @endif
                        @endforeach
                    @else
                        @foreach(\Azuriom\Models\NavbarElement::query()->whereNull('parent_id')->orderBy('position')->get() as $element)
                            @if(!$element->isDropdown())
                                <li><a href="{{ $element->getLink() }}">{{ $element->name }}</a></li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>

            {{-- Legal links --}}
            <div class="ft-links">
                <h6 class="ft-heading" data-footer-field="legal_title">
                    {{ theme_config('footer.legal_title') ?: 'Légal' }}
                </h6>
                <ul id="obsidian-footer-legal-display">
                    @php $legalLinks = theme_config('footer.legal_links') ?? []; @endphp
                    @forelse($legalLinks as $link)
                        @if(!empty($link['name']) && !empty($link['url']))
                            <li><a href="{{ $link['url'] }}">{{ $link['name'] }}</a></li>
                        @endif
                    @empty
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">CGV</a></li>
                    @endforelse
                </ul>
            </div>

            {{-- Social column --}}
            <div class="ft-connect">
                <h6 class="ft-heading" data-footer-field="social_title">
                    {{ theme_config('footer.social_title') ?: trans('theme::messages.footer.social') }}
                </h6>
                <div class="ft-social-cards">
                    @foreach(social_links() as $link)
                        <a href="{{ $link->value }}" title="{{ $link->title }}" target="_blank" rel="noopener noreferrer"
                           class="ft-social-card">
                            <i class="{{ $link->icon }}" style="color:{{ $link->color }}"></i>
                            <span>{{ $link->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="ft-bottom">
            <p>{{ str_replace('{year}', date('Y'), setting('copyright')) }}</p>
            <span class="ft-sep">·</span>
            <p>@lang('messages.copyright')</p>
        </div>
    </div>
</footer>
