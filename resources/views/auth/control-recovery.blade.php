<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>استرجاع دخول التحكم</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <main class="mx-auto flex min-h-screen w-full max-w-md items-center p-6">
        <section class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="mb-2 text-xl font-bold">استرجاع طارئ لدخول التحكم</h1>
            <p class="mb-6 text-sm text-slate-600">
                أدخل البريد المحدد والتوكن السري لتعيين كلمة مرور جديدة ثم الدخول مباشرة.
            </p>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('control.recovery.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium">البريد</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $recoveryEmail) }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="token" class="mb-1 block text-sm font-medium">رمز الاسترجاع</label>
                    <input
                        id="token"
                        name="token"
                        type="password"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium">كلمة المرور الجديدة</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        minlength="8"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium">تأكيد كلمة المرور</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        minlength="8"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                </div>

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    تعيين كلمة المرور والدخول
                </button>
            </form>
        </section>
    </main>
</body>
</html>
