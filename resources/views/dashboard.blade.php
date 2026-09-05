<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] p-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <flux:icon.banknotes class="size-5" />
                    </span>
                    <p class="text-sm font-medium text-gray-500">{{ __('This Month') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-gray-900 tabular-nums">
                    ${{ number_format($monthTotal / 100, 2) }}
                </p>
                <p class="mt-1 text-sm text-gray-400">{{ __('Total spent in') }} {{ now()->format('F Y') }}</p>
            </div>

            <div class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] p-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <flux:icon.clipboard-document-list class="size-5" />
                    </span>
                    <p class="text-sm font-medium text-gray-500">{{ __('Expenses') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-gray-900 tabular-nums">
                    {{ $expenseCount }}
                </p>
                <p class="mt-1 text-sm text-gray-400">{{ __('Recorded this month') }}</p>
            </div>

            <div class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] p-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <flux:icon.tag class="size-5" />
                    </span>
                    <p class="text-sm font-medium text-gray-500">{{ __('Top Category') }}</p>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight text-gray-900 truncate">
                    {{ $topCategory ?? '—' }}
                </p>
                <p class="mt-1 text-sm text-gray-400 tabular-nums">
                    @if ($topCategory)
                        ${{ number_format($topCategoryTotal / 100, 2) }} {{ __('this month') }}
                    @else
                        {{ __('No expenses yet') }}
                    @endif
                </p>
            </div>
        </div>
        {{-- <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-8 bg-transparent">
            <h2 class="text-xl font-bold mb-4">{{ __('Monthly Expenses by Category') }} — {{ now()->format('F Y') }}</h2>
            <canvas id="expensesChart" height="100"></canvas>
        </div>
    </div>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        (function() {
            const canvas = document.getElementById('expensesChart');
            if (!canvas) return; // Prevent errors if the canvas isn't in the DOM

            const ctx = canvas.getContext('2d');

            // Destroy existing chart instance if it exists (important for Livewire re-renders)
            if (window.expensesChartInstance) {
                window.expensesChartInstance.destroy();
            }

            window.expensesChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels'] ?? []),
                    datasets: [{
                        label: '{{ __("Amount") }}',
                        data: @json($chartData['data'] ?? []),
                        backgroundColor: [
                            '#60a5fa', '#fbbf24', '#34d399', '#f87171'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        })();
    </script>

</x-layouts.app>
