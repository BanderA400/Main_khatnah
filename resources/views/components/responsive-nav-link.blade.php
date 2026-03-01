@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-r-4 border-[#8B47D4] text-start text-base font-medium text-[#4C1D95] bg-[#f3e8ff] focus:outline-none focus:text-[#4C1D95] focus:bg-[#e4ccff] focus:border-[#4C1D95] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-r-4 border-transparent text-start text-base font-medium text-[#5e586e] hover:text-[#2d2a38] hover:bg-[#f3f1f6] hover:border-[#d4d0dc] focus:outline-none focus:text-[#2d2a38] focus:bg-[#f3f1f6] focus:border-[#d4d0dc] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
