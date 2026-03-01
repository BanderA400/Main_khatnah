@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-[#443f52] dark:text-[#d4d0dc]']) }}>
    {{ $value ?? $slot }}
</label>
