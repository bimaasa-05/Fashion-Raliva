{{-- GLOBAL CART AJAX HANDLER (used on all customer pages) --}}
<script>
(function () {
    var ADD_URL = '{{ route("customer.cart.add") }}';
    var CSRF = '{{ csrf_token() }}';
    var ROUTES = {
        update: '{{ route("customer.cart.update", ["cartItem" => "__ID__"]) }}',
        destroy: '{{ route("customer.cart.destroy", ["cartItem" => "__ID__"]) }}'
    };

    // Format angka menjadi Rupiah
    function rupiah(n) {
        n = Math.round(Number(n) || 0);
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    // Update semua badge cart di halaman (elemen .cart-badge)
    function updateBadges(count) {
        document.querySelectorAll('.cart-badge').forEach(function (b) {
            if (count > 0) {
                b.textContent = count;
                b.classList.remove('hidden');
            } else {
                b.textContent = '0';
                b.classList.add('hidden');
            }
        });
    }

    // Toast notifikasi
    function showToast(msg) {
        var existing = document.getElementById('cart-toast');
        if (existing) existing.remove();
        var toast = document.createElement('div');
        toast.id = 'cart-toast';
        toast.textContent = msg;
        toast.style.cssText = 'position:fixed;left:50%;bottom:96px;transform:translateX(-50%);background:#1c1b1b;color:#fff;padding:10px 18px;border-radius:999px;font-size:13px;font-family:Manrope,sans-serif;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease;';
        document.body.appendChild(toast);
        requestAnimationFrame(function () { toast.style.opacity = '1'; });
        setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 350); }, 1800);
    }

    // Handler global untuk semua tombol [data-cart-add]
    document.addEventListener('click', function (e) {
        var addBtn = e.target.closest('[data-cart-add]');
        if (addBtn) {
            e.preventDefault();
            e.stopPropagation();

            var variantId = addBtn.getAttribute('data-variant-id');
            if (!variantId) {
                showToast('Pilih warna & ukuran terlebih dahulu.');
                return;
            }

            addBtn.disabled = true;
            fetch(ADD_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_variant_id: variantId })
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                addBtn.disabled = false;
                if (data.status === 'added') {
                    updateBadges(data.count);
                    if (data.message) showToast(data.message);
                } else if (data.message) {
                    showToast(data.message);
                }
            })
            .catch(function () { addBtn.disabled = false; });
            return;
        }

        // Qty dec/inc di halaman cart: [data-cart-dec] / [data-cart-inc]
        var decBtn = e.target.closest('[data-cart-dec]');
        var incBtn = e.target.closest('[data-cart-inc]');
        if (decBtn || incBtn) {
            e.preventDefault();
            e.stopPropagation();
            var btn = decBtn || incBtn;
            var qtyEl = btn.closest('.qty-wrap')?.querySelector('.qty-value');
            var itemId = btn.getAttribute('data-item-id');
            if (!qtyEl || !itemId) return;

            var current = parseInt(qtyEl.textContent, 10) || 1;
            var next = decBtn ? current - 1 : current + 1;
            if (next < 1) next = 1;
            if (next > 99) next = 99;

            updateQty(itemId, next, btn);
        }

        // Remove item cart: [data-cart-remove]
        var rmBtn = e.target.closest('[data-cart-remove]');
        if (rmBtn) {
            e.preventDefault();
            e.stopPropagation();
            var itemIdRm = rmBtn.getAttribute('data-item-id');
            if (!itemIdRm) return;
            removeItem(itemIdRm, rmBtn);
        }
    });

    function updateQty(itemId, qty, btn) {
        var row = btn.closest('[data-cart-row]');
        btn.disabled = true;
        fetch(ROUTES.update.replace('__ID__', itemId), {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity: qty })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            btn.disabled = false;
            if (data.status === 'updated') {
                var qtyEl = row?.querySelector('.qty-value');
                if (qtyEl) qtyEl.textContent = qty;
                var itemTotal = row?.querySelector('[data-item-total]');
                if (itemTotal && data.item_total !== undefined) itemTotal.textContent = rupiah(data.item_total);

                updateBadges(data.count);
                updateSummary(data);
                if (data.message) showToast(data.message);
            } else if (data.message) {
                showToast(data.message);
            }
        })
        .catch(function () { if (btn) btn.disabled = false; });
    }

    function removeItem(itemId, btn) {
        var row = btn.closest('[data-cart-row]');
        if (row) row.style.opacity = '0.5';
        fetch(ROUTES.destroy.replace('__ID__', itemId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.status === 'removed') {
                if (row) row.remove();
                updateBadges(data.count);
                updateSummary(data);
                if (data.message) showToast(data.message);
                checkEmpty();
            }
        })
        .catch(function () { if (row) row.style.opacity = '1'; });
    }

    // Update Order Summary di halaman cart dan badge
    function updateSummary(data) {
        var sub = document.getElementById('cart-subtotal');
        var subItems = document.getElementById('cart-subtotal-items');
        var total = document.getElementById('cart-total');
        var cartTitle = document.getElementById('cart-title');

        if (sub && data.subtotal !== undefined) sub.textContent = rupiah(data.subtotal);
        if (total && data.total !== undefined) total.textContent = rupiah(data.total);
        if (subItems && data.count !== undefined) subItems.textContent = data.count;
        if (cartTitle && data.count !== undefined) {
            cartTitle.textContent = 'CART (' + data.count + ')';
        }
    }

    // Sembunyikan section cart & tampilkan empty state saat kosong
    function checkEmpty() {
        var rows = document.querySelectorAll('[data-cart-row]');
        var empty = document.getElementById('cart-empty');
        var content = document.getElementById('cart-content');
        var footer = document.getElementById('cart-footer');
        if (rows.length === 0 && empty) {
            if (content) content.classList.add('hidden');
            if (footer) footer.classList.add('hidden');
            empty.classList.remove('hidden');
        }
    }

    // Expose helper untuk dipakai halaman lain (misal set count awal)
    window.updateCartBadges = updateBadges;
})();
</script>
