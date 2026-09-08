@props(['icon', 'label', 'href', 'active' => false, 'open'])

<a href="{{ $href }}" 
   class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 
   {{ $active 
      ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' 
      : 'text-slate-400 hover:bg-slate-800 hover:text-blue-400' 
   }}">
    
    <div class="flex items-center justify-center min-w-[40px]">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    </div>

    {{-- PERBAIKAN: Gunakan variabel string dari $open untuk dibaca Alpine --}}
    <span x-show="{{ $open }}" 
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 -translate-x-2"
          x-transition:enter-end="opacity-100 translate-x-0"
          class="ml-3 font-medium text-sm whitespace-nowrap overflow-hidden">
        {{ $label }}
    </span>

    {{-- Dot Active: Gunakan x-show agar hilang saat sidebar ditutup --}}
    @if($active)
        <div x-show="{{ $open }}" class="ml-auto w-1.5 h-1.5 bg-white rounded-full"></div>
    @endif
</a>