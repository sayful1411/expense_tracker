<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold mb-6">{{ __('Expenses') }}</h1>
            <a href="{{ route('expenses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 hover:cursor-pointer">
                {{ __('Add Expense') }}
            </a>
        </div>
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif
        <table class="min-w-full border border-gray-500">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">{{ __('Date') }}</th>
                    <th class="px-4 py-2 border">{{ __('Title') }}</th>
                    <th class="px-4 py-2 border">{{ __('Category') }}</th>
                    <th class="px-4 py-2 border">{{ __('Amount') }}</th>
                    <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-4 py-2 border">{{ $expense->date->format('d F, Y') }}</td>
                        <td class="px-4 py-2 border">{{ $expense->title }}</td>
                        <td class="px-4 py-2 border">{{ $expense->category->name }}</td>
                        <td class="px-4 py-2 border">${{ $expense->amount }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            <a href="{{ route('expenses.edit', $expense) }}"
                                class="text-blue-600 hover:text-blue-800 font-medium">{{ __('Edit') }}</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline ml-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
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