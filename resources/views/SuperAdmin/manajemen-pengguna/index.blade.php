<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - Manajemen Pengguna</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&amp;family=Playfair+Display:wght@500;600&amp;display=swap"
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
                        "label-sm": [
                            "Manrope"
                        ],
                        "body-md": [
                            "Manrope"
                        ],
                        "headline-lg": [
                            "Playfair Display"
                        ],
                        "headline-lg-mobile": [
                            "Playfair Display"
                        ],
                        "title-md": [
                            "Manrope"
                        ],
                        "display-lg": [
                            "Playfair Display"
                        ]
                    },
                    "fontSize": {
                        "label-sm": [
                            "12px",
                            {
                                "lineHeight": "1.0",
                                "letterSpacing": "0.1em",
                                "fontWeight": "700"
                            }
                        ],
                        "body-md": [
                            "16px",
                            {
                                "lineHeight": "1.6",
                                "fontWeight": "400"
                            }
                        ],
                        "headline-lg": [
                            "32px",
                            {
                                "lineHeight": "1.2",
                                "fontWeight": "500"
                            }
                        ],
                        "headline-lg-mobile": [
                            "28px",
                            {
                                "lineHeight": "1.2",
                                "fontWeight": "500"
                            }
                        ],
                        "title-md": [
                            "18px",
                            {
                                "lineHeight": "1.4",
                                "letterSpacing": "0.01em",
                                "fontWeight": "600"
                            }
                        ],
                        "display-lg": [
                            "48px",
                            {
                                "lineHeight": "1.1",
                                "letterSpacing": "-0.02em",
                                "fontWeight": "600"
                            }
                        ]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-surface text-on-surface font-body-md antialiased min-h-screen flex flex-col md:hidden pb-[72px]">
    <!-- TopAppBar -->
    <header
        class="flex justify-between items-center w-full px-container-margin h-16 bg-surface dark:bg-surface border-b border-outline-variant flat no shadows docked full-width top-0 sticky z-40">
        <button aria-label="Menu"
            class="text-on-surface dark:text-on-surface hover:opacity-80 transition-opacity flex items-center justify-center p-2 -ml-2">
            <span class="material-symbols-outlined text-[24px]" data-icon="menu">menu</span>
        </button>
        <div
            class="font-display-lg text-headline-md tracking-widest text-on-surface dark:text-on-surface uppercase absolute left-1/2 -translate-x-1/2">
            RALIVA
        </div>
        <button aria-label="Search"
            class="text-on-surface dark:text-on-surface hover:opacity-80 transition-opacity flex items-center justify-center p-2 -mr-2">
            <span class="material-symbols-outlined text-[24px]" data-icon="search">search</span>
        </button>
    </header>
    <main class="flex-grow pt-4">
        <!-- Page Title & Search -->
        <div class="px-gutter mb-6">
            <h1 class="font-headline-lg-mobile text-headline-lg-mobile mb-4">User Management</h1>
            <div class="relative">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input
                    class="w-full bg-surface-container-low border border-muted-border rounded pl-12 pr-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary transition-colors"
                    placeholder="Search users by name or email..." type="text" />
            </div>
        </div>
        <!-- Filter Chips -->
        <div class="px-gutter mb-8 overflow-x-auto hide-scrollbar">
            <div class="flex gap-2 whitespace-nowrap pb-2">
                <button
                    class="px-4 py-2 bg-primary text-on-primary font-label-sm text-label-sm uppercase rounded transition-colors">All</button>
                <button
                    class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Customer</button>
                <button
                    class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Owner</button>
                <button
                    class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Admin</button>
                <button
                    class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Active</button>
                <button
                    class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Non-active</button>
            </div>
        </div>
        <!-- User List (Bento-ish Grid) -->
        <div class="px-gutter grid gap-element-gap">
            <!-- User Card 1 -->
            <div class="bg-surface-container-low p-4 rounded border border-muted-border relative"
                onclick="openUserDetail()">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-high border border-muted-border">
                            <img class="w-full h-full object-cover"
                                data-alt="A sophisticated headshot portrait of an elegant woman with natural makeup, looking directly at the camera. Minimalist styling, soft studio lighting, high contrast white background, minimalist editorial aesthetic."
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCqx-xulJ7pkm8q6unZz3z9E5L3Mek9tQ-qX6EKC-b3owMZ_2PmkSk6YKmkKWjBPtHAKr1pCX3AMCv2uqiJnlCdWYtWnaIgL3bITzuwd4D15HT5S7is-BYMJO0U1lYiDfwnEx2ox0EPCphcAKGQA2aY4-H1nMvQV4_cL6tfGyfeNLzFm3w5ooxDhG2yMzhWU92lTll0266xX-cBn52Dztu9NrNGi518WvO8f3OaiF8q7WdMqxb3rL43Sw" />
                        </div>
                        <div>
                            <h3 class="font-title-md text-title-md text-on-surface">Eleanor Vance</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant text-sm">eleanor.v@example.com
                            </p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
                </div>
                <div class="flex items-center justify-between mt-2 pt-3 border-t border-muted-border">
                    <div class="flex gap-2">
                        <span
                            class="px-2 py-1 bg-surface-variant text-on-surface font-label-sm text-label-sm uppercase rounded">Owner</span>
                    </div>
                    <div class="flex items-center gap-1 text-sm">
                        <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                        <span
                            class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Active</span>
                    </div>
                </div>
            </div>
            <!-- User Card 2 -->
            <div class="bg-surface-container-low p-4 rounded border border-muted-border relative"
                onclick="openUserDetail()">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-high border border-muted-border flex items-center justify-center text-on-surface-variant font-title-md">
                            MJ
                        </div>
                        <div>
                            <h3 class="font-title-md text-title-md text-on-surface">Marcus James</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant text-sm">mjames@studio.co</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
                </div>
                <div class="flex items-center justify-between mt-2 pt-3 border-t border-muted-border">
                    <div class="flex gap-2">
                        <span
                            class="px-2 py-1 bg-surface-variant text-on-surface font-label-sm text-label-sm uppercase rounded">Customer</span>
                    </div>
                    <div class="flex items-center gap-1 text-sm">
                        <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                        <span
                            class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Non-active</span>
                    </div>
                </div>
            </div>
            <!-- User Card 3 -->
            <div class="bg-surface-container-low p-4 rounded border border-muted-border relative"
                onclick="openUserDetail()">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-high border border-muted-border">
                            <img class="w-full h-full object-cover"
                                data-alt="A portrait of a confident man with short hair wearing a dark minimalist shirt. Soft diffused lighting, clean light grey background, high-end editorial photography style."
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8LkiXGG2IJ7mCqgOOMMqZLeUEntp9kWyket1E3IZZQMa44VGX1CmvoV2UrUoZo7Tb85xJrBagh4yvm7TDrHHhr__loOEjPbzhW785GLPGK-Tr34Ljk0UQeIya8iJ3-M6SOddD2ODLsxnkCwrjdLG6B_C6Xy8hfIqnuzeQ57mmoZCvjhD7RUhzRISgvH74axFQNQoAm4y_vAH-tMFquxdq0Ik4Sj4SGzOdIUvrGhljLMg0tzuyz8BTkg" />
                        </div>
                        <div>
                            <h3 class="font-title-md text-title-md text-on-surface">David Chen</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant text-sm">david.c@raliva.com</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
                </div>
                <div class="flex items-center justify-between mt-2 pt-3 border-t border-muted-border">
                    <div class="flex gap-2">
                        <span
                            class="px-2 py-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase rounded">Admin</span>
                    </div>
                    <div class="flex items-center gap-1 text-sm">
                        <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                        <span
                            class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Active</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center mt-section-gap mb-8">
            <button
                class="px-8 py-3 border border-deep-onyx text-deep-onyx font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors">Load
                More</button>
        </div>
    </main>
    <!-- BottomNavBar -->
    <nav
        class="flex justify-around items-center w-full h-[72px] bg-surface dark:bg-surface px-xs pb-safe border-t border-outline-variant shadow-sm fixed bottom-0 z-50">
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16"
            href="#">
            <span class="material-symbols-outlined text-[24px] mb-1" data-icon="home">home</span>
            <span class="font-label-sm text-[10px] tracking-wider uppercase">Home</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16"
            href="#">
            <span class="material-symbols-outlined text-[24px] mb-1" data-icon="shopping_bag">shopping_bag</span>
            <span class="font-label-sm text-[10px] tracking-wider uppercase">Shop</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16"
            href="#">
            <span class="material-symbols-outlined text-[24px] mb-1" data-icon="favorite">favorite</span>
            <span class="font-label-sm text-[10px] tracking-wider uppercase">Wishlist</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 relative"
            href="#">
            <span class="material-symbols-outlined text-[24px] mb-1" data-icon="shopping_cart">shopping_cart</span>
            <span class="font-label-sm text-[10px] tracking-wider uppercase">Cart</span>
            <span
                class="absolute top-0 right-2 w-4 h-4 bg-secondary-container text-on-secondary-container rounded-full text-[10px] flex items-center justify-center font-bold">2</span>
        </a>
        <a class="flex flex-col items-center justify-center text-secondary dark:text-secondary-fixed-dim scale-95 transition-transform w-16"
            href="#">
            <span class="material-symbols-outlined text-[24px] mb-1" data-icon="person" data-weight="fill"
                style="font-variation-settings: 'FILL' 1;">person</span>
            <span class="font-label-sm text-[10px] tracking-wider uppercase">Account</span>
        </a>
    </nav>
    <!-- User Detail Bottom Sheet Overlay -->
    <div class="fixed inset-0 bg-tertiary/40 z-[60] hidden transition-opacity duration-300 opacity-0 backdrop-blur-sm"
        id="userDetailOverlay" onclick="closeUserDetail()"></div>
    <!-- User Detail Bottom Sheet -->
    <div class="fixed bottom-0 left-0 right-0 bg-surface z-[70] transform translate-y-full transition-transform duration-300 rounded-t-xl border-t border-muted-border flex flex-col max-h-[795px]"
        id="userDetailSheet">
        <div class="flex justify-center pt-3 pb-1 w-full shrink-0" onclick="closeUserDetail()">
            <div class="w-12 h-1 bg-outline-variant rounded-full"></div>
        </div>
        <div class="overflow-y-auto px-container-margin pb-safe pt-4">
            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-16 h-16 rounded-full overflow-hidden bg-surface-container-high border border-muted-border shrink-0">
                    <img class="w-full h-full object-cover"
                        data-alt="A sophisticated headshot portrait of an elegant woman with natural makeup, looking directly at the camera. Minimalist styling, soft studio lighting, high contrast white background, minimalist editorial aesthetic."
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-9hgQtZbsQJEdb7AUHOzcjQI9ZHmBK_beG6Aat8pSOYZAg3XOhHijVJ5sfoIrU_Z747EAjvbFhE5iZBT_8ExSVtZTqYnzuYSm7gXmz2iiLAlvLyqPVM2LEqNXb88L-j8N_BeIzqdvW7U0jYLVz1Smf2rLlB91uOMANjMhnEZp7UT6hjUdp-rUJfIfoSc8YwkCHk9z8By-NumM5JfwBZrkYG6i5HYbQxdSDUkqVhAe755rmlmv7fYewg" />
                </div>
                <div>
                    <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Eleanor Vance</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">eleanor.v@example.com</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-surface-container-low p-3 border border-muted-border rounded">
                    <span
                        class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant block mb-1">Role</span>
                    <span class="font-title-md text-on-surface">Owner</span>
                </div>
                <div class="bg-surface-container-low p-3 border border-muted-border rounded">
                    <span
                        class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant block mb-1">Status</span>
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                        <span class="font-title-md text-on-surface">Active</span>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <h3 class="font-title-md text-title-md mb-3 border-b border-muted-border pb-2">Owned Stores</h3>
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm text-label-sm">
                            LF
                        </div>
                        <span class="font-body-md text-on-surface">Lunara Fashion</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">open_in_new</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-muted-border">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-surface-variant text-on-surface flex items-center justify-center font-label-sm text-label-sm">
                            EH
                        </div>
                        <span class="font-body-md text-on-surface">Eleanor Home</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">open_in_new</span>
                </div>
            </div>
            <div class="mb-8">
                <h3 class="font-title-md text-title-md mb-3 border-b border-muted-border pb-2">Recent Activity</h3>
                <ul class="space-y-3">
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px] mt-0.5">login</span>
                        <div>
                            <p class="font-body-md text-sm text-on-surface">Logged in from new device</p>
                            <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">Oct 24, 2023 •
                                09:41 AM</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="material-symbols-outlined text-on-surface-variant text-[20px] mt-0.5">storefront</span>
                        <div>
                            <p class="font-body-md text-sm text-on-surface">Updated store policy "Lunara Fashion"</p>
                            <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">Oct 22, 2023 •
                                14:20 PM</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="flex flex-col gap-3 pb-8">
                <button
                    class="w-full py-3 bg-surface border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors text-center">Change
                    Role</button>
                <button
                    class="w-full py-3 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest hover:bg-error/20 transition-colors text-center">Deactivate
                    Account</button>
            </div>
        </div>
    </div>
    <script>
        function openUserDetail() {
            const overlay = document.getElementById('userDetailOverlay');
            const sheet = document.getElementById('userDetailSheet');

            overlay.classList.remove('hidden');
            // Small delay to allow display:block to apply before animating opacity
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                sheet.classList.remove('translate-y-full');
            }, 10);
        }

        function closeUserDetail() {
            const overlay = document.getElementById('userDetailOverlay');
            const sheet = document.getElementById('userDetailSheet');

            overlay.classList.add('opacity-0');
            sheet.classList.add('translate-y-full');

            // Wait for transition to finish before hiding
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }
    </script>
</body>

</html>