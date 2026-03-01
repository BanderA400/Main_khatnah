<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-neutral-900/80 backdrop-blur-md border-b border-white/10" id="navbar">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Left side (Logo) -->
        <a href="/" class="flex items-center gap-3 group">
            <div class="relative flex items-center justify-center w-10 h-10">
                <div class="absolute inset-0 bg-primary-500 rounded-xl rotate-45 group-hover:rotate-90 transition-transform duration-500 opacity-20"></div>
                <div class="absolute inset-0 bg-primary-500 rounded-xl -rotate-12 group-hover:rotate-0 transition-transform duration-500 opacity-40"></div>
                <svg xmlns="http://www.w3.org/2000/svg" data-navbar-brand="icon" class="w-5 h-5 text-white relative z-10 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <span data-navbar-brand="text" class="font-amiri text-2xl font-bold text-white tracking-wide transition-colors">ختمة</span>
        </a>

        <!-- Center (Navigation) -->
        <nav class="hidden md:flex items-center gap-8">
            <a href="#features" data-navbar-link="muted" class="text-neutral-300 hover:text-primary-400 font-medium transition-colors">المسارات</a>
            <a href="#how-it-works" data-navbar-link="muted" class="text-neutral-300 hover:text-primary-400 font-medium transition-colors">كيف نبدأ</a>
            <a href="#screenshots" data-navbar-link="muted" class="text-neutral-300 hover:text-primary-400 font-medium transition-colors">لوحة التحكم</a>
            <a href="#faq" data-navbar-link="muted" class="text-neutral-300 hover:text-primary-400 font-medium transition-colors">الأسئلة الشائعة</a>
        </nav>

        <!-- Right side (Actions) -->
        <div class="flex items-center gap-4">
            <a href="/login" data-navbar-link="primary" class="text-white hover:text-primary-400 font-medium transition-colors hidden sm:block">
                تسجيل الدخول
            </a>
            <a href="/register" class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-primary-700 transition-all shadow-[0_0_15px_rgba(91,33,182,0.3)] hover:shadow-[0_0_20px_rgba(91,33,182,0.5)] flex items-center gap-2">
                <span>ابدأ رحلتك مجاناً</span>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden text-white" id="mobile-menu-btn" data-navbar-link="primary" aria-expanded="false" aria-controls="mobile-menu" aria-label="فتح القائمة">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="hidden md:hidden border-t border-white/10 bg-neutral-900/95 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 py-4 space-y-2">
            <a href="#features" class="block py-2 text-neutral-200 hover:text-primary-400 transition-colors">المسارات</a>
            <a href="#how-it-works" class="block py-2 text-neutral-200 hover:text-primary-400 transition-colors">كيف نبدأ</a>
            <a href="#screenshots" class="block py-2 text-neutral-200 hover:text-primary-400 transition-colors">لوحة التحكم</a>
            <a href="#faq" class="block py-2 text-neutral-200 hover:text-primary-400 transition-colors">الأسئلة الشائعة</a>
            <div class="pt-3 flex items-center gap-3">
                <a href="/login" class="px-4 py-2 rounded-lg border border-white/20 text-neutral-100 hover:text-white hover:border-primary-400 transition-colors">
                    تسجيل الدخول
                </a>
                <a href="/register" class="px-4 py-2 rounded-lg bg-primary-600 text-white font-bold hover:bg-primary-700 transition-colors">
                    ابدأ مجاناً
                </a>
            </div>
        </div>
    </div>
</header>
