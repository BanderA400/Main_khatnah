<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ختمة') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/brand/khatma-mark.svg') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="khatma-auth-body antialiased">
        <div class="khatma-auth-shell">
            <aside class="khatma-auth-panel">
                <a href="/" class="khatma-auth-logo-wrap">
                    <x-application-logo class="h-14 w-auto sm:h-16" />
                </a>

                <h1 class="khatma-auth-title">رحلة ختمتك تبدأ بخطة يومية واضحة</h1>
                <p class="khatma-auth-subtitle">نظّم تلاوتك أو حفظك أو مراجعتك، وتابع تقدمك يومًا بيوم من لوحة واحدة.</p>

                <ul class="khatma-auth-highlights">
                    <li>ختمة كاملة أو نطاق مخصص حسب هدفك.</li>
                    <li>وِرد يومي محسوب تلقائيًا مع متابعة مباشرة.</li>
                    <li>إحصائيات واضحة لثباتك والتزامك.</li>
                </ul>
            </aside>

            <main class="khatma-auth-card px-6 py-6 overflow-hidden sm:px-8 sm:py-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
