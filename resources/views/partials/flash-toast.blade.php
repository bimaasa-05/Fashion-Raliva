@if (session('toast'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const message = @js(session('toast.message'));
            const icon = @js(session('toast.icon', 'info'));
            if (typeof window.showRalivaToast === 'function') {
                window.showRalivaToast(message, icon);
            }
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const message = @js($errors->first());
            if (typeof window.showRalivaToast === 'function') {
                window.showRalivaToast(message, 'gpp_bad');
            }
        });
    </script>
@endif
