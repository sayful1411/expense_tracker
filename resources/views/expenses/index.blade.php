<x-layouts.app :title="__('Expenses')">
    <div class="container mx-auto py-10">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Expenses') }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Every expense you have recorded.') }}</p>
                </div>
                <a href="{{ route('expenses.create') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 active:scale-[0.98]">
                    <flux:icon.plus class="size-4" />
                    {{ __('Add Expense') }}
                </a>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-5 py-3.5">{{ __('Date') }}</th>
                                <th class="px-5 py-3.5">{{ __('Title') }}</th>
                                <th class="px-5 py-3.5">{{ __('Category') }}</th>
                                <th class="px-5 py-3.5 text-right">{{ __('Amount') }}</th>
                                <th class="px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($expenses as $expense)
                                <tr class="transition hover:bg-gray-50/60">
                                    <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">
                                        {{ $expense->date->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 font-medium text-gray-900">
                                        {{ $expense->title }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                                            {{ $expense->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-medium text-gray-900 tabular-nums whitespace-nowrap">
                                        ${{ $expense->amount }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                        <a href="{{ route('expenses.edit', $expense) }}"
                                            class="inline-flex items-center justify-center size-8 rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-blue-600 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/10"
                                            aria-label="{{ __('Edit') }}">
                                            <flux:icon.pencil class="size-4" />
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center size-8 rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-red-500/10"
                                                aria-label="{{ __('Delete') }}">
                                                <flux:icon.trash class="size-4" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <flux:icon.receipt-percent class="mx-auto size-10 text-gray-300" />
                                        <p class="mt-3 text-sm font-medium text-gray-900">{{ __('No expenses yet.') }}</p>
                                        <p class="mt-1 text-sm text-gray-500">{{ __('Add your first expense to start tracking.') }}</p>
                                        <a href="{{ route('expenses.create') }}"
                                            class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500">
                                            {{ __('Add Expense') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
