<script>
    (function () {
        if (window.__ralivaSessionGuard) return;
        window.__ralivaSessionGuard = true;

        var loginUrl = '{{ route('login') }}';
        var handling = false;

        function sesiBerakhir() {
            if (handling) return;
            handling = true;
            try {
                sessionStorage.clear();
            } catch (e) {}
            window.location.href = loginUrl + '?expired=1';
        }

        // Semua request fetch yang dibalas 419 (token/sesi kedaluwarsa)
        // langsung diarahkan ke login dengan penanda expired.
        if (window.fetch) {
            var asli = window.fetch;
            window.fetch = function () {
                return asli.apply(this, arguments).then(function (res) {
                    if (res && res.status === 419) sesiBerakhir();
                    return res;
                });
            };
        }

        // Form biasa yang terkena 419 akan menampilkan halaman 419
        // yang ramah (errors/419) — tidak perlu dicegat di sini.
    })();
</script>
