<x-layouts.app :title="__('Monthly Expense Summary')">
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ __('Monthly Expense Summary') }} ({{ now()->format('F Y') }})</h1>
        <table class="min-w-full border border-gray-200 mb-4">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">{{ __('Category') }}</th>
                    <th class="px-4 py-2 border">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-4 py-2 border">
                            {{ $expense->category->name ?? $categories[$expense->category_id] ?? 'Others' }}
                        </td>
                        <td class="px-4 py-2 border">
                            ${{ number_format($expense->total / 100, 2) }}
                        </td>
                    </tr>
                    @php $grandTotal += $expense->total; @endphp
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-4 text-center text-gray-500">
                            {{ __('No expenses found for this month.') }}
                        </td>
                    </tr>
                @endforelse
                <tr>
                    <td class="px-4 py-2 font-bold border">{{ __('Total') }}</td>
                    <td class="px-4 py-2 font-bold border">${{ number_format($grandTotal / 100, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <a href="{{ route('expenses.index') }}" class="text-blue-500 hover:underline">{{ __('Back to Expenses') }}</a>
    </div>
</x-layouts.app>