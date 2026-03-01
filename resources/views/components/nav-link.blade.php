@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#8B47D4] text-sm font-medium leading-5 text-[#2d2a38] focus:outline-none focus:border-[#4C1D95] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#7c7590] hover:text-[#4C1D95] hover:border-[#d4d0dc] focus:outline-none focus:text-[#4C1D95] focus:border-[#d4d0dc] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
