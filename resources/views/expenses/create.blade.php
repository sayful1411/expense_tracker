<x-layouts.app :title="__('Add Expense')">
    <div class="container mx-auto py-10">
        <div class="max-w-lg mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Add Expense') }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Track where your money goes.') }}</p>
                </div>
                <a href="{{ route('expenses.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-gray-900 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/10">
                    {{ __('View All') }}
                    <flux:icon.arrow-right class="size-4" />
                </a>
            </div>

            <form action="{{ route('expenses.store') }}" method="POST"
                class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] p-8">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Title') }}</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-900 placeholder:text-gray-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                            required placeholder="Dinner">
                        @error('title')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Category') }}</label>
                        <select name="category_id" id="category_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                            required>
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Amount') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">$</span>
                                <input type="number" step="any" name="amount" id="amount" value="{{ old('amount') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white pl-7 pr-3.5 py-2.5 text-gray-900 placeholder:text-gray-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                    required placeholder="50">
                            </div>
                            @error('amount')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" value="{{ old('date', now()->toDateString()) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                required>
                            @error('date')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8 pt-5 border-t border-gray-100">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 active:scale-[0.98]">
                        {{ __('Save Expense') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
