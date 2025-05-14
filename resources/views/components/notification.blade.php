@props(['type' => '', 'message' => ''])

@php
    $styles = [
        'info' => 'bg-blue-500 text-white border-blue-600',
        'success' => 'bg-green-500 text-white border-green-600',
    ];

    $style = $styles[$type] ?? $styles['info'];
@endphp

<div x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 5000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 px-6 py-4 border rounded shadow-md w-full max-w-md notification-box {{$type}}"
    role="alert"
>
    <div class="flex items-center space-x-3">
        <!-- Icons -->
        @if($type === 'success')
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        @else
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path d="M13 16h-1v-4h-1m0-4h.01M12 2a10 10 0 110 20 10 10 0 010-20z"
                      stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        @endif

        <!-- Message -->
        <span class="text-sm font-medium">{{ $message }}</span>
    </div>
</div>

