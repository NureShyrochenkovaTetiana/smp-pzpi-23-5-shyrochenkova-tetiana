<header class="sticky top-0 z-50 w-full border-b bg-white/95 backdrop-blur border-b-slate-300 supports-[backdrop-filter]:bg-white/60">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                           stroke-width="2">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8c0 5.5-4.78 10-10 10"
                            />
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
                        </g>
                    </svg>
                </div>
                <span class="text-xl font-bold text-green-600">
                Весна
              </span>
            </div>
            <nav class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-sm font-medium transition-colors hover:text-green-600">
                    Головна
                </a>
                <a href="/goods" class="text-sm font-medium transition-colors hover:text-green-600">
                    Товари
                </a>
            </nav>
            <?php if(isset($_SESSION['username'])) : ?>
                <div class="inline-flex gap-4">
                    <a href="/profile" class="flex items-center justify-center size-9 rounded-md text-base text-white bg-green-600 hover:bg-green-700 px-2 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24"><!-- Icon from Lucide by Lucide Contributors - https://github.com/lucide-icons/lucide/blob/main/LICENSE --><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></g></svg>
                    </a>
                    <a href="/cart" class="flex items-center justify-center min-w-32 h-9 rounded-md text-base text-white bg-green-600 hover:bg-green-700 px-2 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 11l-1 9m5-9l-4-7M2 11h20M3.5 11l1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4M4.5 15.5h15M5 11l4-7m0 7l1 9" />
                        </svg>
                        Кошик
                    </a>
                </div>
            <?php else : ?>
                <a href="/login" class="flex items-center justify-center min-w-32 h-9 rounded-md text-base text-white bg-green-600 hover:bg-green-700 px-2 gap-2">
                    Увійти в акаунт
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>