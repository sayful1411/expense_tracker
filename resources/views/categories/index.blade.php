<x-layouts.app :title="__('Categories')">
    <div class="container mx-auto py-10">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Categories') }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Organize your expenses into your own categories.') }}</p>
                </div>
                <a href="{{ route('categories.create') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/30 active:scale-[0.98]">
                    <flux:icon.plus class="size-4" />
                    {{ __('Add Category') }}
                </a>
            </div>

            <div class="bg-white rounded-2xl ring-1 ring-gray-950/5 shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-5 py-3.5">{{ __('Name') }}</th>
                                <th class="px-5 py-3.5 text-right">{{ __('Expenses') }}</th>
                                <th class="px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($categories as $category)
                                <tr class="transition hover:bg-gray-50/60">
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                                            {{ $category->name }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-gray-500 tabular-nums">
                                        {{ $category->expenses_count }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="inline-flex items-center justify-center size-8 rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-blue-600 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/10"
                                            aria-label="{{ __('Edit') }}">
                                            <flux:icon.pencil class="size-4" />
                                        </a>

                                        <flux:modal.trigger name="confirm-deletion-{{ $category->id }}">
                                            <button type="button"
                                                class="inline-flex items-center justify-center size-8 rounded-lg text-gray-400 transition hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-red-500/10"
                                                aria-label="{{ __('Delete') }}">
                                                <flux:icon.trash class="size-4" />
                                            </button>
                                        </flux:modal.trigger>

                                        <flux:modal name="confirm-deletion-{{ $category->id }}" class="max-w-sm text-start whitespace-normal">
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="space-y-6">
                                                @csrf
                                                @method('DELETE')

                                                <flux:heading size="lg">{{ __('Delete category?') }}</flux:heading>

                                                <flux:subheading>
                                                    {{ __('":name" will be permanently deleted. Categories with expenses cannot be deleted.', ['name' => $category->name]) }}
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
                                    <td colspan="3" class="px-5 py-16 text-center">
                                        <flux:icon.tag class="mx-auto size-10 text-gray-300" />
                                        <p class="mt-3 text-sm font-medium text-gray-900">{{ __('No categories yet.') }}</p>
                                        <p class="mt-1 text-sm text-gray-500">{{ __('Add your first category to organize expenses.') }}</p>
                                        <a href="{{ route('categories.create') }}"
                                            class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500">
                                            {{ __('Add Category') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
