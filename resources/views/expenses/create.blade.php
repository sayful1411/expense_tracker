<x-layouts.app :title="__('Add Expense')">
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ __('Add Expense') }}</h1>
        <form action="{{ route('expenses.store') }}" method="POST" class="max-w-lg mx-auto p-6 rounded shadow">
            @csrf

            <div class="mb-4">
                <label for="title" class="block font-semibold mb-1">{{ __('Title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2" required placeholder="Dinner">
                @error('title')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block font-semibold mb-1">{{ __('Category') }}</label>
                <select name="category_id" id="category_id" class="w-full border bg-black border-gray-300 rounded px-3 py-2" required>
                    <option value="">{{ __('Select Category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="amount" class="block font-semibold mb-1">{{ __('Amount') }}</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2" required placeholder="50.00">
                @error('amount')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="date" class="block font-semibold mb-1">{{ __('Date') }}</label>
                <input type="date" name="date" id="date" value="{{ old('date', now()->toDateString()) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2" required>
                @error('date')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>