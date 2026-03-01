<section class="bg-neutral-900 py-32 px-6 relative overflow-hidden text-white" id="screenshots">
    <!-- Glowing background effects -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[radial-gradient(circle,rgba(91,33,182,0.15)_0%,transparent_70%)] pointer-events-none"></div>
    
    <div class="max-w-6xl mx-auto relative z-10">
        <div class="text-center mb-20 reveal-up">
            <h2 class="font-amiri text-4xl md:text-5xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-white to-white/70">
                تجربة مستخدم لا مثيل لها
            </h2>
            <p class="text-neutral-400 text-lg max-w-2xl mx-auto">
                لوحة تحكم أنيقة تضع كل ما تحتاجه بين يديك بوضوح وسهولة تامة.
            </p>
        </div>

        <!-- Main Dashboard Image in Mac-like Frame -->
        <div class="reveal-up" style="transition-delay: 0.2s">
            <div class="rounded-2xl border border-white/10 bg-black/50 p-2 md:p-4 backdrop-blur-xl shadow-2xl mb-16 transform hover:scale-[1.01] transition-transform duration-700">
                <div class="flex gap-2 px-2 pb-3 pt-1">
                    <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                </div>
                <div class="rounded-xl overflow-hidden border border-white/5 relative">
                    <img src="{{ asset('images/landing/screenshots/dashboard.png') }}" alt="لوحة التحكم" class="w-full h-auto object-cover object-center" loading="lazy" />
                    <!-- Overlay gradient to blend bottom -->
                    <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>
            </div>
        </div>

        <!-- Features Carousel (Simplified for HTML/CSS without React/Embla) -->
        <div class="reveal-up" style="transition-delay: 0.4s">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Item 1 -->
                <div class="group">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-2 overflow-hidden mb-4 transition-all duration-500 group-hover:bg-white/10 group-hover:border-primary-500/30">
                        <img src="{{ asset('images/landing/screenshots/dashboard.png') }}" alt="متابعة الورد" class="w-full h-auto rounded-xl object-cover aspect-video opacity-80 group-hover:opacity-100 transition-opacity duration-500" loading="lazy" />
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1">متابعة الورد</h4>
                    <p class="text-neutral-500 text-sm">تصميم مريح للقراءة والتتبع</p>
                </div>
                
                <!-- Item 2 -->
                <div class="group">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-2 overflow-hidden mb-4 transition-all duration-500 group-hover:bg-white/10 group-hover:border-primary-500/30">
                        <img src="{{ asset('images/landing/screenshots/history1.png') }}" alt="إدارة الختمات" class="w-full h-auto rounded-xl object-cover aspect-video opacity-80 group-hover:opacity-100 transition-opacity duration-500" loading="lazy" />
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1">إدارة الختمات</h4>
                    <p class="text-neutral-500 text-sm">نظرة شاملة لكل خططك</p>
                </div>
                
                <!-- Item 3 -->
                <div class="group">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-2 overflow-hidden mb-4 transition-all duration-500 group-hover:bg-white/10 group-hover:border-primary-500/30">
                        <img src="{{ asset('images/landing/screenshots/history2.png') }}" alt="إحصائيات تفصيلية" class="w-full h-auto rounded-xl object-cover aspect-video opacity-80 group-hover:opacity-100 transition-opacity duration-500" loading="lazy" />
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1">إحصائيات تفصيلية</h4>
                    <p class="text-neutral-500 text-sm">رسوم بيانية لتحفيزك</p>
                </div>
            </div>
        </div>
    </div>
</section>
