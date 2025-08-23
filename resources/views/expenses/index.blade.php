<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold mb-6">{{ __('Expenses') }}</h1>
            <a href="{{ route('expenses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 hover:cursor-pointer">
                {{ __('Add Expense') }}
            </a>
        </div>
        <table class="min-w-full border border-gray-500">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">{{ __('Date') }}</th>
                    <th class="px-4 py-2 border">{{ __('Title') }}</th>
                    <th class="px-4 py-2 border">{{ __('Category') }}</th>
                    <th class="px-4 py-2 border">{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-4 py-2 border">{{ $expense->date->format('d F, Y') }}</td>
                        <td class="px-4 py-2 border">{{ $expense->title }}</td>
                        <td class="px-4 py-2 border">{{ $expense->category->name }}</td>
                        <td class="px-4 py-2 border">${{ $expense->amount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            {{ __('No expenses found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $expenses->links() }}   
        </div>
    </div>
</x-layouts.app>