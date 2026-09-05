<x-layouts.app :title="__('Expenses')">
    <div class="container mx-auto py-10">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('Expenses') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Every expense you have recorded.') }}</p>
                </div>
                <a href="{{ route('expenses.create') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 active:scale-[0.98]">
                    <flux:icon.plus class="size-4" />
                    {{ __('Add Expense') }}
                </a>
            </div>

            <div class="bg-white dark:bg-transparent rounded-2xl ring-1 ring-gray-950/5 dark:ring-white/10 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-white/5 border-b border-gray-100 dark:border-white/10 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                <th class="px-5 py-3.5">{{ __('Date') }}</th>
                                <th class="px-5 py-3.5">{{ __('Title') }}</th>
                                <th class="px-5 py-3.5">{{ __('Category') }}</th>
                                <th class="px-5 py-3.5 text-right">{{ __('Amount') }}</th>
                                <th class="px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                            @forelse($expenses as $expense)
                                <tr class="transition hover:bg-gray-50/60 dark:hover:bg-white/5">
                                    <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        {{ $expense->date->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">
                                        {{ $expense->title }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-white/10 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-300">
                                            {{ $expense->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-medium text-gray-900 dark:text-white tabular-nums whitespace-nowrap">
                                        ${{ $expense->amount }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                        <a href="{{ route('expenses.edit', $expense) }}"
                                            class="inline-flex items-center justify-center size-8 rounded-lg text-gray-400 dark:text-gray-500 transition hover:bg-gray-100 dark:hover:bg-white/10 hover:text-blue-600 dark:hover:text-blue-400 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/10"
                                            aria-label="{{ __('Edit') }}">
                                            <flux:icon.pencil class="size-4" />
                                        </a>

                                        <flux:modal.trigger name="confirm-deletion-{{ $expense->id }}">
                                            <button type="button"
                                                class="inline-flex items-center justify-center size-8 rounded-lg text-gray-400 dark:text-gray-500 transition hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-red-500/10"
                                                aria-label="{{ __('Delete') }}">
                                                <flux:icon.trash class="size-4" />
                                            </button>
                                        </flux:modal.trigger>

                                        <flux:modal name="confirm-deletion-{{ $expense->id }}" class="max-w-sm text-start whitespace-normal">
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="space-y-6">
                                                @csrf
                                                @method('DELETE')

                                                <flux:heading size="lg">{{ __('Delete expense?') }}</flux:heading>

                                                <flux:subheading>
                                                    {{ __('":title" will be permanently deleted. This action cannot be undone.', ['title' => $expense->title]) }}
                                                </flux:subheading>

                                                <div class="flex justify-end gap-2">
                                                    <flux:modal.close>
                                                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                                                    </flux:modal.close>
                                                    <flux:button variant="danger" type="submit">{{ __('Delete') }}</flux:button>
                                                </div>
                                            </form>
                                        </flux:modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <flux:icon.receipt-percent class="mx-auto size-10 text-gray-300 dark:text-gray-600" />
                                        <p class="mt-3 text-sm font-medium text-gray-900 dark:text-white">{{ __('No expenses yet.') }}</p>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Add your first expense to start tracking.') }}</p>
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
