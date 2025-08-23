<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        {{-- <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-8 bg-transparent">
            <h2 class="text-xl font-bold mb-4">{{ __('Monthly Expenses by Category') }}</h2>
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
