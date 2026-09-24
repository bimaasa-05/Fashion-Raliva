<style>
    /* Brand RALIVA maroon #8B1E3F — dipakai layout Produksi agar seragam dengan
       SuperAdmin/Admin/Owner/Gudang. Sumber warna status ada di App\Support\StatusStyle. */
    :root {
        --c-secondary: 139 30 63;
        --color-secondary-container: #8B1E3F;
        --color-secondary-fixed: #8B1E3F;
        --color-secondary-fixed-dim: #8B1E3F;
        --color-on-secondary-fixed-variant: #6D1428;
        --color-on-secondary-fixed: #6D1428;
        --color-on-secondary-container: #6D1428;
        --color-gold-accent: #8B1E3F;
    }
    .dark {
        --c-secondary: 139 30 63;
    }
    .bg-gold-accent { background-color: #8B1E3F !important; }
    .bg-gold-accent\/5 { background-color: rgba(139,30,63,0.05) !important; }
    .bg-gold-accent\/10 { background-color: rgba(139,30,63,0.1) !important; }
    .bg-gold-accent\/15 { background-color: rgba(139,30,63,0.15) !important; }
    .bg-gold-accent\/20 { background-color: rgba(139,30,63,0.2) !important; }
    .bg-gold-accent\/25 { background-color: rgba(139,30,63,0.25) !important; }
    .bg-gold-accent\/30 { background-color: rgba(139,30,63,0.3) !important; }
    .bg-gold-accent\/40 { background-color: rgba(139,30,63,0.4) !important; }
    .bg-gold-accent\/50 { background-color: rgba(139,30,63,0.5) !important; }
    .bg-gold-accent\/60 { background-color: rgba(139,30,63,0.6) !important; }
    .bg-gold-accent\/70 { background-color: rgba(139,30,63,0.7) !important; }
    .text-gold-accent { color: #8B1E3F !important; }
    .text-gold-accent\/5 { color: rgba(139,30,63,0.05) !important; }
    .text-gold-accent\/10 { color: rgba(139,30,63,0.1) !important; }
    .text-gold-accent\/15 { color: rgba(139,30,63,0.15) !important; }
    .text-gold-accent\/20 { color: rgba(139,30,63,0.2) !important; }
    .text-gold-accent\/25 { color: rgba(139,30,63,0.25) !important; }
    .text-gold-accent\/30 { color: rgba(139,30,63,0.3) !important; }
    .text-gold-accent\/40 { color: rgba(139,30,63,0.4) !important; }
    .text-gold-accent\/50 { color: rgba(139,30,63,0.5) !important; }
    .text-gold-accent\/60 { color: rgba(139,30,63,0.6) !important; }
    .text-gold-accent\/70 { color: rgba(139,30,63,0.7) !important; }
    .text-gold-accent\/80 { color: rgba(139,30,63,0.8) !important; }
    .border-gold-accent { border-color: #8B1E3F !important; }
    .border-gold-accent\/10 { border-color: rgba(139,30,63,0.1) !important; }
    .border-gold-accent\/15 { border-color: rgba(139,30,63,0.15) !important; }
    .border-gold-accent\/20 { border-color: rgba(139,30,63,0.2) !important; }
    .border-gold-accent\/25 { border-color: rgba(139,30,63,0.25) !important; }
    .border-gold-accent\/30 { border-color: rgba(139,30,63,0.3) !important; }
    .border-gold-accent\/40 { border-color: rgba(139,30,63,0.4) !important; }
    .border-gold-accent\/50 { border-color: rgba(139,30,63,0.5) !important; }
    .border-gold-accent\/60 { border-color: rgba(139,30,63,0.6) !important; }
    .border-gold-accent\/70 { border-color: rgba(139,30,63,0.7) !important; }
    .border-t-gold-accent\/70 { border-top-color: rgba(139,30,63,0.7) !important; }
    .text-gold-accent\/\[0\.06\] { color: rgba(139,30,63,0.06) !important; }
    .bg-gold-accent\/\[0\.06\] { background-color: rgba(139,30,63,0.06) !important; }
    .bg-secondary { background-color: rgb(139 30 63) !important; }
    .bg-secondary\/10 { background-color: rgba(139,30,63,0.1) !important; }
    .bg-secondary\/15 { background-color: rgba(139,30,63,0.15) !important; }
    .bg-secondary\/20 { background-color: rgba(139,30,63,0.2) !important; }
    .bg-secondary\/30 { background-color: rgba(139,30,63,0.3) !important; }
    .bg-secondary-container { background-color: #8B1E3F !important; }
    .bg-secondary-container\/10 { background-color: rgba(139,30,63,0.1) !important; }
    .bg-secondary-container\/15 { background-color: rgba(139,30,63,0.15) !important; }
    .bg-secondary-container\/20 { background-color: rgba(139,30,63,0.2) !important; }
    .bg-secondary-container\/25 { background-color: rgba(139,30,63,0.25) !important; }
    .bg-secondary-container\/30 { background-color: rgba(139,30,63,0.3) !important; }
    .bg-secondary-fixed { background-color: #8B1E3F !important; }
    .bg-secondary-fixed-dim { background-color: #8B1E3F !important; }
    .text-secondary { color: rgb(139 30 63) !important; }
    .text-secondary\/70 { color: rgba(139,30,63,0.7) !important; }
    .text-on-secondary-container { color: #6D1428 !important; }
    .text-on-secondary-fixed { color: #6D1428 !important; }
    .text-on-secondary-fixed-variant { color: #6D1428 !important; }
    .border-secondary { border-color: rgb(139 30 63) !important; }
    .border-secondary\/20 { border-color: rgba(139,30,63,0.2) !important; }
    .border-secondary\/30 { border-color: rgba(139,30,63,0.3) !important; }
    .border-secondary-container { border-color: #8B1E3F !important; }
    .border-secondary-container\/20 { border-color: rgba(139,30,63,0.2) !important; }
    .border-secondary-container\/30 { border-color: rgba(139,30,63,0.3) !important; }
    .hover\:bg-gold-accent:hover { background-color: #8B1E3F !important; }
    .hover\:text-gold-accent:hover { color: #8B1E3F !important; }
    .hover\:border-gold-accent:hover { border-color: #8B1E3F !important; }
    .focus\:border-gold-accent:focus { border-color: #8B1E3F !important; }
    .focus\:ring-gold-accent:focus { --tw-ring-color: rgba(139,30,63,0.1) !important; }
    .group:hover .group-hover\:text-gold-accent { color: #8B1E3F !important; }
    .group:hover .group-hover\:border-gold-accent { border-color: #8B1E3F !important; }
    .group\/row:hover .group-hover\/row\:text-gold-accent { color: #8B1E3F !important; }
    .from-gold-accent { --tw-gradient-from: #8B1E3F !important; --tw-gradient-to: rgb(139 30 63 / 0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/5 { --tw-gradient-from: rgba(139,30,63,0.05) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/10 { --tw-gradient-from: rgba(139,30,63,0.1) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/15 { --tw-gradient-from: rgba(139,30,63,0.15) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/20 { --tw-gradient-from: rgba(139,30,63,0.2) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/25 { --tw-gradient-from: rgba(139,30,63,0.25) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .via-gold-accent\/10 { --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), rgba(139,30,63,0.1), var(--tw-gradient-to) !important; }
    .from-gold-accent\/45 { --tw-gradient-from: rgba(139,30,63,0.45) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/40 { --tw-gradient-from: rgba(139,30,63,0.4) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/60 { --tw-gradient-from: rgba(139,30,63,0.6) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-gold-accent\/70 { --tw-gradient-from: rgba(139,30,63,0.7) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-secondary-container { --tw-gradient-from: #8B1E3F !important; --tw-gradient-to: rgb(139 30 63 / 0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-secondary-container\/5 { --tw-gradient-from: rgba(139,30,63,0.05) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-secondary-container\/15 { --tw-gradient-from: rgba(139,30,63,0.15) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-secondary-container\/20 { --tw-gradient-from: rgba(139,30,63,0.2) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-secondary-container\/30 { --tw-gradient-from: rgba(139,30,63,0.3) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .to-gold-accent { --tw-gradient-to: #8B1E3F !important; }
    .to-gold-accent\/5 { --tw-gradient-to: rgba(139,30,63,0.05) !important; }
    .via-gold-accent\/5 { --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), rgba(139,30,63,0.05), var(--tw-gradient-to) !important; }
    .via-gold-accent\/40 { --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), rgba(139,30,63,0.4), var(--tw-gradient-to) !important; }
    .to-secondary-container\/5 { --tw-gradient-to: rgba(139,30,63,0.05) !important; }
    .hover\:from-gold-accent\/70:hover { --tw-gradient-from: rgba(139,30,63,0.7) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .hover\:shadow-\[0_0_12px_rgba\(201\,162\,77\,0\.35\)\]:hover { --tw-shadow: 0 0 12px rgba(139,30,63,0.35) !important; --tw-shadow-colored: 0 0 12px rgba(139,30,63,0.35) !important; box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow) !important; }
    .drop-shadow-\[0_0_6px_rgba\(201\,162\,77\,0\.35\)\] { --tw-drop-shadow: drop-shadow(0 0 6px rgba(139,30,63,0.35)) !important; filter: var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow) !important; }
    .border-l-gold-accent { border-left-color: #8B1E3F !important; }
    .shadow-\[0_0_0_3px_rgba\(201\,162\,77\,0\.08\)\] { --tw-shadow: 0 0 0 3px rgba(139,30,63,0.08) !important; --tw-shadow-colored: 0 0 0 3px rgba(139,30,63,0.08) !important; box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow) !important; }
    .text-gradient-gold { background: linear-gradient(115deg, #6D1428 0%, #8B1E3F 35%, #c03a5a 55%, #8B1E3F 80%, #6D1428 100%) !important; -webkit-background-clip: text !important; background-clip: text !important; color: transparent !important; }
    .card-premium:hover { border-color: rgb(139 30 63 / 0.45) !important; }
    .premium-heading::before { background: #8B1E3F !important; }
    .premium-table tbody tr td:first-child::before { background: #8B1E3F !important; }
    .hero-glow::before { background: radial-gradient(circle at 70% 30%, rgba(139, 30, 63, 0.14), transparent 45%), radial-gradient(circle at 15% 85%, rgba(139, 30, 63, 0.08), transparent 40%) !important; }
    .gauge-progress { filter: drop-shadow(0 0 6px rgba(139, 30, 63, 0.45)) !important; }
    #sidebar-tip-global { border-color: rgba(139, 30, 63, .45) !important; }
    .timeline-line::before { background: linear-gradient(to bottom, rgba(139,30,63,0.55), rgba(139,30,63,0.06)) !important; }
</style>