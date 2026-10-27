<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Café Categories
        </h2>
    </x-slot>

    <div style="padding-top: 32px; padding-bottom: 32px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 flex flex-wrap gap-3">
                <a href="{{ route('categories.create') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    Add Category
                </a>

                <a href="{{ route('products.index') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    View Products
                </a>

                <a href="{{ route('customers.index') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    View Customers
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Category</th>
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-left">Products</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-6 py-4">{{ $category->name }}</td>
                                <td class="px-6 py-4">{{ $category->description ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $category->products_count }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="text-blue-700 underline">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('categories.destroy', $category) }}"
                                              onsubmit="return confirm('Delete this category?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-700 underline">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-600">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>