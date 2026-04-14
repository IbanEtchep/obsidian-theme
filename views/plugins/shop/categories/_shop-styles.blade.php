<style>
/* ═══════════════════════════════════════
   LOOT VAULT — Shop Styles
   ═══════════════════════════════════════ */

/* Layout */
.sv-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    align-items: start;
}
.sv-aside { position: sticky; top: 90px; }

@media (max-width: 991.98px) {
    .sv-layout { grid-template-columns: 1fr; }
    .sv-aside { position: static; }
}

/* ── MAIN ── */
.sv-main-head { margin-bottom: 1.25rem; }
.sv-title {
    font-size: clamp(1.4rem, 3vw, 2rem);
    color: #fff;
    margin: 0;
}

.sv-desc {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    color: var(--obsidian-text);
    font-size: .9rem;
    line-height: 1.6;
}
.sv-desc p:last-child { margin-bottom: 0; }

.sv-welcome {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    color: var(--obsidian-text);
    font-size: .95rem;
}
.sv-welcome > i {
    font-size: 2rem;
    color: var(--obsidian-primary);
    flex-shrink: 0;
}

/* ── PRODUCT GRID ── */
.sv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.sv-item {
    display: flex;
    flex-direction: column;
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    overflow: hidden;
    text-decoration: none;
    color: var(--obsidian-text);
    transition: transform .3s cubic-bezier(.4,0,.2,1), border-color .3s, box-shadow .3s;
    position: relative;
}
.sv-item::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--obsidian-primary), transparent);
    opacity: 0;
    transition: opacity .3s;
    z-index: 2;
}
.sv-item:hover {
    transform: translateY(-6px);
    border-color: rgba(var(--obsidian-primary-rgb),.25);
    box-shadow: 0 12px 36px rgba(0,0,0,.3), var(--obsidian-glow);
    color: var(--obsidian-text);
}
.sv-item:hover::before { opacity: 1; }

/* Image */
.sv-item-img {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
    background: var(--obsidian-surface-2);
}
.sv-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s cubic-bezier(.4,0,.2,1);
}
.sv-item:hover .sv-item-img img { transform: scale(1.08); }

.sv-item-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: var(--obsidian-border);
}

.sv-item-badge {
    position: absolute;
    top: .5rem; right: .5rem;
    padding: .2rem .6rem;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 800;
    font-size: .65rem;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #0a0a0f;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: .25rem;
    z-index: 1;
}

/* Body */
.sv-item-body {
    padding: .85rem .85rem .5rem;
    flex: 1;
}
.sv-item-name {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    text-transform: uppercase;
    letter-spacing: .02em;
    color: #fff;
    margin: 0 0 .35rem;
    line-height: 1.2;
}
.sv-item-price {
    display: flex;
    align-items: baseline;
    gap: .4rem;
}
.sv-item-old {
    font-size: .75rem;
    color: var(--obsidian-text-dim);
}
.sv-item-current {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--obsidian-primary);
}

/* CTA bar at bottom */
.sv-item-cta {
    padding: .6rem .85rem;
    border-top: 1px solid var(--obsidian-border);
    text-align: center;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--obsidian-text-dim);
    transition: color .2s, background .2s;
}
.sv-item:hover .sv-item-cta {
    color: var(--obsidian-primary);
    background: rgba(var(--obsidian-primary-rgb),.04);
}

/* Empty */
.sv-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    color: var(--obsidian-text-dim);
}
.sv-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }

/* ── SIDEBAR ── */
.sv-sidebar {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}

/* User card */
.sv-user {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1rem;
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
}
.sv-user-avatar {
    width: 42px; height: 42px;
    border-radius: .5rem;
    object-fit: cover;
    border: 2px solid var(--obsidian-border);
}
.sv-user-name {
    display: block;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    color: #fff;
}
.sv-user-balance {
    display: block;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 1rem;
    color: var(--obsidian-primary);
}

/* Action buttons */
.sv-actions {
    display: flex;
    flex-direction: column;
    gap: .35rem;
}
.sv-action-btn {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .55rem .85rem;
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    color: var(--obsidian-text);
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    transition: all .2s;
    text-decoration: none;
    cursor: pointer;
}
.sv-action-btn:hover {
    color: #fff;
    border-color: rgba(var(--obsidian-primary-rgb),.3);
    background: rgba(var(--obsidian-primary-rgb),.06);
}
.sv-action-credit {
    background: rgba(var(--obsidian-primary-rgb),.08);
    border-color: rgba(var(--obsidian-primary-rgb),.2);
    color: var(--obsidian-primary);
}
.sv-action-credit:hover {
    background: rgba(var(--obsidian-primary-rgb),.2);
    border-color: rgba(var(--obsidian-primary-rgb),.4);
    color: var(--obsidian-primary);
}
.sv-action-logout {
    background: transparent;
    border-color: transparent;
    color: var(--obsidian-text-dim);
    font-size: .75rem;
}

/* Panels */
.sv-panel {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    overflow: hidden;
}
.sv-panel-head {
    padding: .7rem 1rem;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--obsidian-text-dim);
    background: rgba(var(--obsidian-primary-rgb),.03);
    border-bottom: 1px solid var(--obsidian-border);
}
.sv-panel-body { padding: .85rem 1rem; }

/* Category nav */
.sv-nav {
    display: flex;
    flex-direction: column;
}
.sv-nav-item {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .6rem 1rem;
    color: var(--obsidian-text);
    font-size: .85rem;
    font-weight: 500;
    text-decoration: none;
    transition: all .15s;
    border-left: 2px solid transparent;
}
.sv-nav-item i { font-size: .8rem; color: var(--obsidian-text-dim); width: 16px; text-align: center; }
.sv-nav-item:hover {
    background: rgba(var(--obsidian-primary-rgb),.04);
    color: #fff;
}
.sv-nav-item.--active {
    background: rgba(var(--obsidian-primary-rgb),.08);
    color: #fff;
    border-left-color: var(--obsidian-primary);
}
.sv-nav-item.--active i { color: var(--obsidian-primary); }
.sv-nav-sub { padding-left: 2.25rem; font-size: .8rem; }

/* Goal */
.sv-goal-bar {
    height: 6px;
    background: var(--obsidian-surface-2);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: .4rem;
}
.sv-goal-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--obsidian-primary), var(--obsidian-accent));
    border-radius: 3px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
}
.sv-goal-text {
    font-size: .75rem;
    color: var(--obsidian-text-dim);
    text-align: center;
    display: block;
}

/* Top customer */
.sv-top {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.sv-top-avatar {
    width: 40px; height: 40px;
    border-radius: .5rem;
    object-fit: cover;
}
.sv-top-name {
    display: block;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: .85rem;
    color: #fff;
}
.sv-top-amount {
    display: block;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: .8rem;
    color: var(--obsidian-primary);
}

/* Recent payments */
.sv-recent-list { padding: .4rem 0; }
.sv-recent {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .45rem 1rem;
}
.sv-recent-avatar {
    width: 28px; height: 28px;
    border-radius: .375rem;
    object-fit: cover;
    flex-shrink: 0;
}
.sv-recent-name {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--obsidian-text);
}
.sv-recent-meta {
    display: block;
    font-size: .7rem;
    color: var(--obsidian-text-dim);
}
.sv-recent-empty {
    padding: .75rem 1rem;
    font-size: .8rem;
    color: var(--obsidian-text-dim);
}

/* ── Modal override ── */
#itemModal .modal-content {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    color: var(--obsidian-text);
}
#itemModal .modal-header {
    border-bottom: 1px solid var(--obsidian-border);
    background: rgba(var(--obsidian-primary-rgb),.04);
}
#itemModal .modal-header .modal-title {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: #fff;
}
#itemModal .modal-footer {
    border-top: 1px solid var(--obsidian-border);
    background: rgba(var(--obsidian-primary-rgb),.02);
}
#itemModal .modal-footer .font-weight-bold,
#itemModal .modal-footer .fw-bold {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--obsidian-primary);
}

/* Responsive grid */
@media (max-width: 767.98px) {
    .sv-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: .75rem; }
    .sv-item-name { font-size: .8rem; }
    .sv-item-current { font-size: .95rem; }
}
</style>
