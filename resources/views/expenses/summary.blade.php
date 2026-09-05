@php
    $presets = [
        __('Today') => [now()->toDateString(), now()->toDateString()],
        __('Last 7 days') => [now()->subDays(6)->toDateString(), now()->toDateString()],
        __('This month') => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
        __('Last month') => [now()->subMonthNoOverflow()->startOfMonth()->toDateString(), now()->subMonthNoOverflow()->endOfMonth()->toDateString()],
        __('This year') => [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()],
        __('Last year') => [now()->subYear()->startOfYear()->toDateString(), now()->subYear()->endOfYear()->toDateString()],
    ];

    $activePreset = null;
    foreach ($presets as $label => [$presetFrom, $presetTo]) {
        if ($start->toDateString() === $presetFrom && $end->toDateString() === $presetTo) {
            $activePreset = $label;
        }
    }
@endphp

<x-layouts.app :title="__('Expense Summary')">
    <div class="container mx-auto py-10">
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Expense Summary') }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $start->format('d M, Y') }} — {{ $end->format('d M, Y') }}
                </p>
            </div>

            <form action="{{ route('expenses.summary') }}" method="GET"
                class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] p-6 mb-6">
                <input type="hidden" name="from" value="{{ $from }}">
                <input type="hidden" name="to" value="{{ $to }}">

                <div class="flex flex-wrap items-center gap-2">
                    @foreach($presets as $label => [$presetFrom, $presetTo])
                        <a href="{{ route('expenses.summary', ['from' => $presetFrom, 'to' => $presetTo]) }}"
                            @class([
                                'rounded-full px-3.5 py-1.5 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/10',
                                'bg-blue-600 text-white shadow-sm' => $activePreset === $label,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200' => $activePreset !== $label,
                            ])>
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="flex flex-col sm:flex-row sm:items-end gap-3 mt-4 pt-4 border-t border-gray-100">
                    <div class="flex-1">
                        <label for="custom-range" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Custom range') }}</label>
                        <input type="text" id="custom-range" data-flatpickr-range data-from="{{ $from }}" data-to="{{ $to }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-900 placeholder:text-gray-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                            placeholder="{{ __('Pick a start and end date') }}" readonly>
                    </div>
                    <a href="{{ route('expenses.summary') }}"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/10">
                        {{ __('Reset') }}
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 active:scale-[0.98]">
                        {{ __('Apply') }}
                    </button>
                </div>
            </form>

            <div class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th class="px-5 py-3.5">{{ __('Category') }}</th>
                            <th class="px-5 py-3.5 text-right">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($expenses as $expense)
                            <tr class="transition hover:bg-gray-50/60">
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                                        {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right font-medium text-gray-900 tabular-nums">
                                    ${{ number_format($expense->total / 100, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-5 py-16 text-center">
                                    <flux:icon.receipt-percent class="mx-auto size-10 text-gray-300" />
                                    <p class="mt-3 text-sm font-medium text-gray-900">{{ __('No expenses in this period.') }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('Try a different preset or date range.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="bg-gray-50/80 border-t border-gray-100">
                            <td class="px-5 py-3.5 font-semibold text-gray-900">{{ __('Total') }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-gray-900 tabular-nums">
                                ${{ number_format($total / 100, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('expenses.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800">
                    <flux:icon.arrow-left class="size-4" />
                    {{ __('Back to Expenses') }}
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
