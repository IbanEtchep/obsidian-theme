document.addEventListener('DOMContentLoaded', function () {
    initNavbarScroll();
    initRevealAnimations();
});

function initNavbarScroll() {
    var navbar = document.getElementById('obsidian-navbar');
    if (!navbar) return;
    function update() {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    }
    window.addEventListener('scroll', update, { passive: true });
    update();
}

function initRevealAnimations() {
    var els = document.querySelectorAll('.obsidian-reveal');
    if (!els.length) return;
    if (!('IntersectionObserver' in window)) {
        els.forEach(function (el) { el.classList.add('visible'); });
        return;
    }
    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (el, i) {
        el.style.transitionDelay = (i % 3) * 0.1 + 's';
        obs.observe(el);
    });
}
