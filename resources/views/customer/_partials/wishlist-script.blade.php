{{-- GLOBAL WISHLIST AJAX HANDLER (used on all customer pages) --}}
<style>
    /* Wishlist icon active state (burgundy) — overrides hover/text defaults */
    [data-wishlist-toggle].wishlisted-active,
    [data-wishlist-toggle].wishlisted-active .material-symbols-outlined {
        color: #8B1E3F !important;
    }
    [data-wishlist-toggle].wishlisted-active .material-symbols-outlined {
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
<script>
(function () {
    var TOGGLE_URL = '{{ route("customer.wishlist.toggle") }}';
    var CSRF = '{{ csrf_token() }}';
    var AUTHD = {{ auth()->check() ? 'true' : 'false' }};

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-wishlist-toggle]');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        if (!AUTHD) {
            window.location.href = '{{ route("login", ["redirect" => "__REDIRECT__"]) }}'.replace('__REDIRECT__', encodeURIComponent(window.location.pathname + window.location.search));
            return;
        }

        var productId = btn.getAttribute('data-product-id');
        if (!productId) return;

        btn.disabled = true;

        fetch(TOGGLE_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            btn.disabled = false;
            var icon = btn.querySelector('.material-symbols-outlined');
            if (icon) {
                if (data.wishlisted) {
                    btn.classList.add('wishlisted-active');
                    icon.setAttribute('data-icon', 'favorite');
                    icon.textContent = 'favorite';
                    icon.setAttribute('data-weight', 'fill');
                } else {
                    btn.classList.remove('wishlisted-active');
                    icon.setAttribute('data-icon', 'favorite_border');
                    icon.textContent = 'favorite_border';
                    icon.removeAttribute('data-weight');
                }
            }
            if (data.message) {
                showWlToast(data.message);
            }
        })
        .catch(function () {
            btn.disabled = false;
        });
    });

    function showWlToast(msg) {
        var existing = document.getElementById('wl-toast');
        if (existing) existing.remove();
        var toast = document.createElement('div');
        toast.id = 'wl-toast';
        toast.textContent = msg;
        toast.style.cssText = 'position:fixed;left:50%;bottom:96px;transform:translateX(-50%);background:#1c1b1b;color:#fff;padding:10px 18px;border-radius:999px;font-size:13px;font-family:Manrope,sans-serif;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease;';
        document.body.appendChild(toast);
        requestAnimationFrame(function () { toast.style.opacity = '1'; });
        setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 350); }, 1800);
    }
})();
</script>
