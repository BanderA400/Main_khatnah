<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#5B21B6] border border-transparent rounded-md font-semibold text-sm text-white hover:bg-[#4C1D95] focus:bg-[#4C1D95] active:bg-[#3B1578] focus:outline-none focus:ring-2 focus:ring-[#6D28D9] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
