<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - Payouts &amp; Withdrawals</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:wght@500;600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-background": "#1b1c1c",
                        "on-surface": "#1b1c1c",
                        "surface-container": "#efeded",
                        "surface-variant": "#e3e2e2",
                        "tertiary-fixed": "#e3e2df",
                        "secondary-container": "#fdd177",
                        "gold-accent": "#C9A24D",
                        "on-error": "#ffffff",
                        "background": "#fbf9f9",
                        "on-tertiary-container": "#848482",
                        "on-secondary-fixed-variant": "#5c4300",
                        "on-primary-fixed": "#1c1b1b",
                        "surface-dim": "#dbdad9",
                        "surface-container-high": "#e9e8e7",
                        "on-primary-fixed-variant": "#474646",
                        "on-secondary-fixed": "#261a00",
                        "error": "#ba1a1a",
                        "on-primary-container": "#858383",
                        "tertiary": "#000000",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#464745",
                        "on-error-container": "#93000a",
                        "primary-fixed-dim": "#c8c6c5",
                        "error-container": "#ffdad6",
                        "surface-container-highest": "#e3e2e2",
                        "surface-container-low": "#f5f3f3",
                        "primary-container": "#1c1b1b",
                        "outline-variant": "#c4c7c7",
                        "inverse-primary": "#c8c6c5",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed-dim": "#ebc168",
                        "warm-bg": "#FBF9F9",
                        "on-primary": "#ffffff",
                        "primary": "#000000",
                        "tertiary-container": "#1a1c1a",
                        "secondary": "#795905",
                        "surface": "#fbf9f9",
                        "secondary-fixed": "#ffdf9f",
                        "inverse-on-surface": "#f2f0f0",
                        "on-tertiary-fixed": "#1a1c1a",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "surface-bright": "#fbf9f9",
                        "muted-border": "#E9E8E7",
                        "surface-tint": "#5f5e5e",
                        "outline": "#747878",
                        "primary-fixed": "#e5e2e1",
                        "deep-onyx": "#111111",
                        "on-surface-variant": "#444748",
                        "on-secondary": "#ffffff",
                        "inverse-surface": "#303031",
                        "on-secondary-container": "#775804"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "element-gap": "12px",
                        "gutter": "16px",
                        "unit": "4px",
                        "section-gap": "64px",
                        "container-margin": "24px"
                    },
                    "fontFamily": {
                        "label-sm": ["Manrope"],
                        "body-md": ["Manrope"],
                        "headline-lg": ["Playfair Display"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "title-md": ["Manrope"],
                        "display-lg": ["Playfair Display"]
                    },
                    "fontSize": {
                        "label-sm": ["12px", { "lineHeight": "1.0", "letterSpacing": "0.1em", "fontWeight": "700" }],
                        "body-md": ["16px", { "lineHeight": "1.6", "fontWeight": "400" }],
                        "headline-lg": ["32px", { "lineHeight": "1.2", "fontWeight": "500" }],
                        "headline-lg-mobile": ["28px", { "lineHeight": "1.2", "fontWeight": "500" }],
                        "title-md": ["18px", { "lineHeight": "1.4", "letterSpacing": "0.01em", "fontWeight": "600" }],
                        "display-lg": ["48px", { "lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #fbf9f9;
            color: #1b1c1c;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }

        /* Hide scrollbar for clean look */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-background antialiased flex flex-col min-h-screen relative overflow-x-hidden">
    <!-- TopAppBar (Semantic Intent: Admin Dashboard/Finance, so navigation shell is omitted for focused task context, but user explicitly asked for top bar in the context of the app, let's keep it minimal) -->
    <header
        class="flex justify-between items-center w-full px-container-margin h-16 bg-surface dark:bg-surface text-on-surface dark:text-on-surface border-b border-outline-variant docked full-width top-0 z-40 sticky">
        <div class="flex items-center gap-4">
            <button class="hover:opacity-80 transition-opacity">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <h1 class="font-display-lg text-headline-md tracking-widest text-on-surface dark:text-on-surface">RALIVA
            </h1>
        </div>
        <div class="flex items-center gap-4">
            <button class="hover:opacity-80 transition-opacity">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="hover:opacity-80 transition-opacity relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
            </button>
        </div>
    </header>
    <main class="flex-grow pb-32">
        <div class="max-w-4xl mx-auto px-container-margin md:px-0 py-section-gap">
            <div class="mb-section-gap flex flex-col md:flex-row md:items-end justify-between gap-element-gap">
                <div>
                    <nav aria-label="Breadcrumb" class="flex text-sm text-on-surface-variant mb-2">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a class="inline-flex items-center hover:text-primary transition-colors font-label-sm text-label-sm"
                                    href="#">
                                    ADMIN
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                                    <span aria-current="page"
                                        class="text-on-surface font-label-sm text-label-sm">PAYOUTS</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h2
                        class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary">
                        Withdrawal Requests</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-2">Manage store owner payout
                        requests.</p>
                </div>
                <div class="flex gap-4">
                    <button
                        class="border border-outline px-4 py-2 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">filter_list</span> Filter
                    </button>
                    <button
                        class="bg-deep-onyx text-on-primary px-4 py-2 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">download</span> Export
                    </button>
                </div>
            </div>
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-section-gap">
                <div class="bg-surface-container-lowest border border-muted-border p-container-margin">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-surface-container-high flex items-center justify-center rounded-full">
                            <span class="material-symbols-outlined text-on-surface">pending_actions</span>
                        </div>
                        <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">
                            Pending Requests</h3>
                    </div>
                    <p class="font-display-lg text-display-lg text-primary">24</p>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-2">Awaiting review and approval</p>
                </div>
                <div class="bg-surface-container-lowest border border-muted-border p-container-margin">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-secondary-container flex items-center justify-center rounded-full">
                            <span class="material-symbols-outlined text-secondary">account_balance_wallet</span>
                        </div>
                        <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Total
                            Pending Amount</h3>
                    </div>
                    <p class="font-display-lg text-display-lg text-gold-accent">Rp 124.500.000</p>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-2">Total nominal requested</p>
                </div>
            </div>
            <!-- List -->
            <div class="bg-surface-container-lowest border border-muted-border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-muted-border bg-surface-container-low">
                                <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">
                                    Store / Owner</th>
                                <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">
                                    Request Details</th>
                                <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Bank
                                    Info</th>
                                <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">
                                    Status</th>
                                <th
                                    class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-muted-border">
                            <!-- Row 1 -->
                            <tr class="hover:bg-surface-container-low transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container border border-muted-border flex-shrink-0"
                                            data-alt="A minimalist logo for a high-end fashion brand named LUNARA on a stark white background. The logo uses elegant, thin serif typography. Professional lighting, high resolution."
                                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB6gSd4J2PIh3JZuCYrMfvjsomc-aS1zHF6R_XE7bcCrDwYXs_9H2qsT7Mdr7BELaYepmq93ClhcjQXbeZXwOcFZVTxmDJ5aaEnuL2IuAifLWX_SdsjBZLDbOo1kPvz5Pciu0chObk75KYcnlP2IcJ54_rk0R_uOGdwvgXBkGfzSPIQaJFqYwOaErSYUcokdckdx5A-Jd36tAhqYJzCbT9L3KYD0GjVvidFHo7ythLSlGnRL2Mn_S8qrw')">
                                        </div>
                                        <div>
                                            <p class="font-title-md text-title-md text-primary">LUNARA Fashion</p>
                                            <p class="font-body-md text-sm text-on-surface-variant">Sarah Jenkins</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-title-md text-title-md text-gold-accent">Rp 12.500.000</p>
                                    <p class="font-body-md text-sm text-on-surface-variant">May 12, 2024</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-body-md text-body-md text-primary">BCA</p>
                                    <p class="font-body-md text-sm text-on-surface-variant">**** **** 4321</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-sm bg-surface-container-high border border-outline-variant font-label-sm text-xs text-on-surface uppercase">
                                        Pending
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div
                                        class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            class="w-8 h-8 flex items-center justify-center border border-outline text-on-surface hover:bg-error hover:text-on-error hover:border-error transition-colors"
                                            onclick="document.getElementById('reject-dialog').classList.remove('hidden')"
                                            title="Reject">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </button>
                                        <button
                                            class="w-8 h-8 flex items-center justify-center bg-deep-onyx text-on-primary hover:opacity-80 transition-opacity"
                                            onclick="document.getElementById('approve-dialog').classList.remove('hidden')"
                                            title="Approve">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr class="hover:bg-surface-container-low transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container border border-muted-border flex-shrink-0"
                                            data-alt="A minimalist logo for a high-end fashion brand named NOIRÉ Studio on a stark white background. The logo uses elegant, thin sans-serif typography. Professional lighting, high resolution."
                                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuClDMBcvZ0LNR-HPA-GAYTIaZ9JzVc5_AZPw34saowaYnaql4RZ0n55UZnCRRiYw3OnKzdsP3B6Oq4IwwaCDADtjVrSMQ8oxTXeUgyqZbFdpN1Km5wK_vTGwTYgYpq223_8YEmcI-1P-LJi8EX2Pa5WDMCDvLTEUsI08E8jM73QrEIk4fsI3RkYZC6xHb2NCP2kSyfDXnaj2XJuewlCxT3PsPpcw5fSfnNcI-v1riWD4xIJzXq8bgD-OQ')">
                                        </div>
                                        <div>
                                            <p class="font-title-md text-title-md text-primary">NOIRÉ Studio</p>
                                            <p class="font-body-md text-sm text-on-surface-variant">David Chen</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-title-md text-title-md text-gold-accent">Rp 8.750.000</p>
                                    <p class="font-body-md text-sm text-on-surface-variant">May 11, 2024</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-body-md text-body-md text-primary">Mandiri</p>
                                    <p class="font-body-md text-sm text-on-surface-variant">**** **** 9876</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-sm bg-surface-container-high border border-outline-variant font-label-sm text-xs text-on-surface uppercase">
                                        Pending
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div
                                        class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            class="w-8 h-8 flex items-center justify-center border border-outline text-on-surface hover:bg-error hover:text-on-error hover:border-error transition-colors"
                                            title="Reject">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </button>
                                        <button
                                            class="w-8 h-8 flex items-center justify-center bg-deep-onyx text-on-primary hover:opacity-80 transition-opacity"
                                            title="Approve">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <!-- Dialogs -->
    <div class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-tertiary/50 backdrop-blur-sm"
        id="approve-dialog">
        <div class="bg-surface-container-lowest border border-muted-border p-section-gap max-w-md w-full shadow-2xl">
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4">Confirm Payout</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-8">You are about to approve a payout of <span
                    class="font-title-md text-gold-accent">Rp 12.500.000</span> to LUNARA Fashion. This action will mark
                the request as processed.</p>
            <div class="flex justify-end gap-4">
                <button
                    class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors"
                    onclick="document.getElementById('approve-dialog').classList.add('hidden')">Cancel</button>
                <button
                    class="bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity">Confirm
                    Approval</button>
            </div>
        </div>
    </div>
    <div class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-tertiary/50 backdrop-blur-sm"
        id="reject-dialog">
        <div class="bg-surface-container-lowest border border-error/20 p-section-gap max-w-md w-full shadow-2xl">
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-error mb-4">Reject Payout</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-4">Are you sure you want to reject this
                payout request for LUNARA Fashion?</p>
            <div class="mb-8">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Reason for
                    Rejection</label>
                <textarea
                    class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary h-24"
                    placeholder="Enter reason..."></textarea>
            </div>
            <div class="flex justify-end gap-4">
                <button
                    class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors"
                    onclick="document.getElementById('reject-dialog').classList.add('hidden')">Cancel</button>
                <button
                    class="bg-error text-on-error px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity">Reject
                    Request</button>
            </div>
        </div>
    </div>
</body>

</html>