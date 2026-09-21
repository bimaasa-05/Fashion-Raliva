<script>
    (function () {
        document.querySelectorAll('form#profil-form, form#password-form').forEach(function (form) {
            form.addEventListener('submit', function () {
                form.querySelectorAll('button[type="submit"]').forEach(function (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-60', 'cursor-not-allowed');
                });
            });
        });
    })();
</script>