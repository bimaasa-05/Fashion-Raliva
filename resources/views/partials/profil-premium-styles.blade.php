@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

    .text-gradient-gold {
        background: linear-gradient(115deg, #6D1428 0%, #8B1E3F 35%, #c03a5a 55%, #8B1E3F 80%, #6D1428 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-glow::before {
        content: '';
        position: absolute;
        inset: -30%;
        background: radial-gradient(circle at 70% 30%, rgba(139, 30, 63, 0.14), transparent 45%),
                    radial-gradient(circle at 15% 85%, rgba(139, 30, 63, 0.08), transparent 40%);
        pointer-events: none;
    }

    @keyframes riseIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .rise { opacity: 0; animation: riseIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    .rise-d1 { animation-delay: 0.1s; }
    .rise-d2 { animation-delay: 0.2s; }
    .rise-d3 { animation-delay: 0.3s; }

    .photo-upload-wrapper { position: relative; }
    .photo-upload-wrapper input[type="file"] { display: none; }
    .photo-preview { transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .photo-preview:hover { transform: scale(1.03); }
    .photo-upload-label { transition: all 0.2s ease; }
    .photo-upload-label:hover { background-color: #8B1E3F; filter: brightness(1.12); }

    /* ============ PREMIUM HERO ============ */
    .profil-hero {
        box-shadow: 0 1px 2px rgb(17 17 17 / 0.04), 0 28px 64px -28px rgb(139 30 63 / 0.55);
        transition: box-shadow 0.3s ease;
    }
    .profil-hero:hover { box-shadow: 0 2px 4px rgb(17 17 17 / 0.05), 0 36px 84px -32px rgb(139 30 63 / 0.7); }
    .profil-hero::after {
        content: '';
        position: absolute;
        left: 14px; right: 14px; top: 0;
        height: 2px;
        border-radius: 9999px;
        background: linear-gradient(90deg, transparent, rgba(139, 30, 63, 0.5) 20%, #e8bf6e 50%, rgba(139, 30, 63, 0.5) 80%, transparent);
        pointer-events: none;
    }

    /* Corner ornaments (brackets) */
    .hero-ornt { position: absolute; width: 30px; height: 30px; border-color: rgba(139, 30, 63, 0.55); z-index: 2; pointer-events: none; }
    .hero-ornt.tl { top: 14px; left: 14px; border-top: 2px solid; border-left: 2px solid; border-radius: 8px 0 0 0; }
    .hero-ornt.tr { top: 14px; right: 14px; border-top: 2px solid; border-right: 2px solid; border-radius: 0 8px 0 0; }
    .hero-ornt.bl { bottom: 14px; left: 14px; border-bottom: 2px solid; border-left: 2px solid; border-radius: 0 0 0 8px; }
    .hero-ornt.br { bottom: 14px; right: 14px; border-bottom: 2px solid; border-right: 2px solid; border-radius: 0 0 8px 0; }

    /* Gold dust */
    .gold-dust { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
    .gold-dust i {
        position: absolute; width: 5px; height: 5px; border-radius: 9999px;
        background: radial-gradient(circle, rgba(232,191,110,0.95), rgba(232,191,110,0) 72%);
        opacity: 0; animation: dustFloat 13s ease-in-out infinite;
    }
    .gold-dust i:nth-child(1) { left: 12%; top: 24%; }
    .gold-dust i:nth-child(2) { left: 84%; top: 16%; width: 6px; height: 6px; animation-delay: -4s; }
    .gold-dust i:nth-child(3) { left: 72%; top: 74%; animation-delay: -7s; }
    .gold-dust i:nth-child(4) { left: 26%; top: 80%; width: 4px; height: 4px; animation-delay: -2.5s; }
    .gold-dust i:nth-child(5) { left: 93%; top: 52%; width: 4px; height: 4px; animation-delay: -9s; }
    .gold-dust i:nth-child(6) { left: 5%; top: 58%; width: 6px; height: 6px; animation-delay: -5.5s; }
    @keyframes dustFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: 0; }
        12% { opacity: 0.6; }
        50% { transform: translateY(-20px) scale(0.8); opacity: 0.25; }
        88% { opacity: 0.5; }
    }

    /* Shimmer nama (burgundy + gold core) */
    @keyframes shimmerText {
        0% { background-position: 0% center; }
        100% { background-position: -220% center; }
    }
    .name-shimmer {
        background-image: linear-gradient(115deg, #6D1428 0%, #8B1E3F 32%, #e8bf6e 50%, #8B1E3F 68%, #6D1428 100%);
        background-size: 220% auto;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: shimmerText 7s linear infinite;
    }

    /* Avatar ring emas + status pulse */
    .avatar-ring {
        background: linear-gradient(140deg, #8B1E3F 0%, #c03a5a 45%, #e8bf6e 72%, #8B1E3F 100%);
        border-radius: 9999px;
        padding: 3px;
        box-shadow: 0 10px 34px -10px rgba(139, 30, 63, 0.6), inset 0 0 0 1px rgba(255,255,255,0.06);
        transition: box-shadow 0.3s ease;
    }
    .avatar-ring:hover {
        box-shadow: 0 14px 44px -12px rgba(139, 30, 63, 0.75), 0 0 0 4px rgba(201, 162, 77, 0.18), inset 0 0 0 1px rgba(255,255,255,0.08);
    }
    .status-dot {
        position: absolute; bottom: 4px; left: -2px; width: 16px; height: 16px;
        border-radius: 9999px; background: #22c55e;
        border: 3px solid var(--c-sc-lowest, #f4eff3);
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.5);
        z-index: 3;
    }
    .status-dot::after {
        content: '';
        position: absolute; inset: -5px; border-radius: 9999px;
        border: 2px solid rgba(34, 197, 94, 0.55);
        animation: statusPulse 2.2s ease-out infinite;
    }
    @keyframes statusPulse {
        0% { transform: scale(0.55); opacity: 1; }
        70% { transform: scale(1.6); opacity: 0; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    /* Stat mini tile */
    .profil-stat {
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 3px;
        padding: 12px 8px; border-radius: 14px; min-width: 0; text-align: center;
        background: rgba(139, 30, 63, 0.05);
        border: 1px solid rgba(139, 30, 63, 0.2);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .profil-stat:hover { transform: translateY(-2px); border-color: rgba(201, 162, 77, 0.55); box-shadow: 0 10px 22px -12px rgba(139, 30, 63, 0.5); }
    .profil-stat .material-symbols-outlined { font-size: 18px; color: #8B1E3F; }
    .profil-stat .lbl { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.14em; color: rgb(var(--c-on-muted) / 1); }
    .profil-stat .val { font-size: 12px; font-weight: 700; color: rgb(var(--c-on-surface) / 1); max-width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
    .dark .profil-stat { background: rgba(201, 162, 77, 0.06); border-color: rgba(201, 162, 77, 0.22); }
    .dark .profil-stat .material-symbols-outlined { color: #e8bf6e; }

    /* ============ PREMIUM CARDS ============ */
    .profil-card { position: relative; }
    .profil-card::before {
        content: '';
        position: absolute; left: 0; right: 0; top: 0; height: 2px;
        border-radius: 9999px 9999px 0 0;
        background: linear-gradient(90deg, transparent, rgba(139, 30, 63, 0.5) 22%, #c03a5a 50%, rgba(139, 30, 63, 0.5) 78%, transparent);
        pointer-events: none;
    }

    /* Icon tile section header */
    .icon-tile {
        position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden;
        background: linear-gradient(140deg, #8B1E3F, #c03a5a);
        box-shadow: 0 8px 20px -8px rgba(139, 30, 63, 0.6), inset 0 0 0 1px rgba(255,255,255,0.1);
    }
    .icon-tile::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.25), transparent 60%);
        pointer-events: none;
    }
    .icon-tile .material-symbols-outlined { color: #fff; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2); }

    /* Input focus glow */
    .profil-input { transition: border-color 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease; }
    .profil-input:hover:not(:focus):not(:disabled) { border-color: rgba(139, 30, 63, 0.4); }
    .profil-input:focus {
        border-color: #8B1E3F;
        box-shadow: 0 0 0 3px rgba(139, 30, 63, 0.14), 0 0 0 1px rgba(201, 162, 77, 0.28);
        outline: none;
    }

    /* Button sheen sweep */
    .btn-sheen { position: relative; overflow: hidden; }
    .btn-sheen::after {
        content: '';
        position: absolute; top: 0; bottom: 0; left: -80%; width: 55%;
        transform: skewX(-20deg);
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        pointer-events: none;
        transition: left 0.65s ease;
    }
    .btn-sheen:hover::after { left: 135%; }

    /* ============ MOTION GUARDS ============ */
    @media (prefers-reduced-motion: reduce) {
        .rise, .name-shimmer, .gold-dust i, .status-dot::after { animation: none !important; }
        .name-shimmer { background-image: linear-gradient(115deg, #6D1428, #8B1E3F, #c03a5a, #8B1E3F, #6D1428) !important; background-size: 100% auto !important; }
        .gold-dust i { opacity: 0.4 !important; }
        .photo-preview:hover, .avatar-ring, .profil-stat:hover { transform: none !important; }
    }
    html[data-motion="minimal"] .rise { animation: none !important; opacity: 1 !important; transform: none !important; pointer-events: auto !important; }
    html[data-motion="minimal"] .name-shimmer { animation: none !important; background-image: linear-gradient(115deg, #6D1428, #8B1E3F, #c03a5a, #8B1E3F, #6D1428) !important; background-size: 100% auto !important; }
    html[data-motion="minimal"] .gold-dust i, html[data-motion="minimal"] .status-dot::after { animation: none !important; opacity: 0.4 !important; }
    html[data-motion="minimal"] * { transition-duration: 0.05s !important; transition-delay: 0s !important; }
</style>
@endpush