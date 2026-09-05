@props(['message' => null])

@php
    $message = $message ?? (session('status') ?? session('error'));
    $variant = session()->has('error') ? 'error' : 'success';
@endphp

@if ($message)
    <div
        x-data="{ visible: false }"
        x-init="requestAnimationFrame(() => visible = true); setTimeout(() => visible = false, 4500)"
        x-show="visible"
        x-cloak
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="translate-x-[calc(100%+1.5rem)] opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-[calc(100%+1.5rem)] opacity-0"
        class="fixed bottom-4 right-4 z-[60] max-w-sm"
        role="status"
        aria-live="polite"
    >
        <div class="flex items-start gap-3 rounded-xl bg-white px-4 py-3.5 ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)]">
            <span @class([
                'mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full',
                'bg-green-100 text-green-600' => $variant === 'success',
                'bg-red-100 text-red-600' => $variant === 'error',
            ])>
                @if ($variant === 'success')
                    <flux:icon.check-circle class="size-3.5" />
                @else
                    <flux:icon.exclamation-triangle class="size-3.5" />
                @endif
            </span>

            <p class="text-sm font-medium text-gray-900 pt-px">{{ $message }}</p>

            <button type="button" x-on:click="visible = false"
                class="ml-2 -mt-0.5 -mr-1 flex size-6 shrink-0 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/10"
                aria-label="{{ __('Dismiss') }}">
                <flux:icon.x-mark class="size-4" />
            </button>
        </div>
    </div>
@endif
