<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ختمة - رفيقك اليومي مع القرآن</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite([
        'resources/css/app.css',
        'resources/css/landing/main.css',
        'resources/js/app.js',
        'resources/js/landing/animations.js',
    ])
</head>
<body class="font-sans antialiased text-neutral-900 bg-white">

    @include('landing.partials.navbar')

    <main>
        @include('landing.partials.hero')
        @include('landing.partials.features')
        @include('landing.partials.how-it-works')
        
        {{-- Custom Section for Screenshots (since it was built) --}}
        @include('landing.partials.screenshots')
        
        {{-- FAQ --}}
        @include('landing.partials.faq')
    </main>

    @include('landing.partials.footer')

</body>
</html>
