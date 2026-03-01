<x-guest-layout>
    <div class="khatma-auth-form-head">
        <h2 class="khatma-auth-form-title">تأكيد كلمة المرور</h2>
        <p class="khatma-auth-form-note">
            هذه خطوة أمان إضافية. أدخل كلمة المرور للمتابعة.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" value="كلمة المرور" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                تأكيد
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
