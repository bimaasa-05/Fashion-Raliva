<script>
    (function () {
        function bindPhotoPreview(input) {
            var target = document.getElementById(input.getAttribute('data-photo-preview'));
            if (!target) return;
            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function (e) {
                    target.src = e.target.result;
                    target.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
        }
        window.addEventListener('load', function () {
            document.querySelectorAll('input[data-photo-preview]').forEach(bindPhotoPreview);
        });
    })();
</script>