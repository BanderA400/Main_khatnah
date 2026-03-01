@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'rounded-lg border-[#d4d0dc] bg-white/95 text-[#2d2a38] shadow-sm placeholder:text-[#9b95aa] focus:border-[#6D28D9] focus:ring-[#6D28D9] dark:border-[#5e586e] dark:bg-[#1a1822] dark:text-[#f3f1f6] dark:placeholder:text-[#9089a3]']) }}
>
