{{-- GLOBAL FOLLOW STORE AJAX HANDLER (semua tab halaman toko) --}}
@php
$i18nFollow = [
    'Anda belum login.' => __('Anda belum login.'),
    'Ikuti Toko' => __('Ikuti Toko'),
    'Diikuti' => __('Diikuti'),
    'pengikut' => __('pengikut'),
];
@endphp
<script>
window.RALIVA_I18N = Object.assign(window.RALIVA_I18N || {}, @json($i18nFollow));
window.ralivaT = window.ralivaT || function (s) { var m = window.RALIVA_I18N || {}; return m[s] || s; };
</script>
<style>
    /* State "sudah ikuti": outline burgundy menggantikan emas */
    [data-follow-store].is-following {
        background-color: transparent !important;
        color: var(--chrome-accent) !important;
        box-shadow: inset 0 0 0 1px var(--chrome-accent);
    }
    [data-follow-store].is-following::after { display: none; }
</style>
<script>
(function () {
    var CSRF = '{{ csrf_token() }}';
    var AUTHD = {{ auth()->check() ? 'true' : 'false' }};
    var btn = document.querySelector('[data-follow-store]');
    if (!btn) return;
    var label = btn.querySelector('[data-follow-label]');
    var countEl = document.querySelector('[data-follow-count]');

    btn.addEventListener('click', function () {
        if (!AUTHD) {
            showFollowToast(window.ralivaT('Anda belum login.'));
            return;
        }

        var url = btn.getAttribute('data-follow-url');
        if (!url) return;

        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            btn.disabled = false;
            if (data.status === 'error') {
                showFollowToast(data.message);
                return;
            }
            btn.classList.toggle('is-following', !!data.followed);
            if (label) label.textContent = window.ralivaT(data.followed ? 'Diikuti' : 'Ikuti Toko');
            if (countEl) countEl.textContent = data.followers_count + ' ' + window.ralivaT('pengikut');
            if (data.message) showFollowToast(data.message);
        })
        .catch(function () { btn.disabled = false; });
    });

    function showFollowToast(msg) {
        var existing = document.getElementById('follow-toast');
        if (existing) existing.remove();
        var toast = document.createElement('div');
        toast.id = 'follow-toast';
        toast.textContent = msg;
        toast.style.cssText = 'position:fixed;left:50%;bottom:96px;transform:translateX(-50%);background:#1c1b1b;color:#fff;padding:10px 18px;border-radius:999px;font-size:13px;font-family:Manrope,sans-serif;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease;';
        document.body.appendChild(toast);
        requestAnimationFrame(function () { toast.style.opacity = '1'; });
        setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 350); }, 1800);
    }
})();
</script>
