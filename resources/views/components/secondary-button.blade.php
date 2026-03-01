<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-[#d4d0dc] rounded-md font-semibold text-sm text-[#443f52] shadow-sm hover:bg-[#f3f1f6] focus:outline-none focus:ring-2 focus:ring-[#6D28D9] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
