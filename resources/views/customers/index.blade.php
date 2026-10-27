<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Café Customers
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

            <div class="mb-4 flex gap-3">
                <a href="{{ route('customers.create') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    Add Customer
                </a>

                <a href="{{ route('orders.index') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    View Orders
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Phone</th>
                            <th class="px-6 py-3 text-left">Address</th>
                            <th class="px-6 py-3 text-left">Orders</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="px-6 py-4">
                                    {{ $customer->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $customer->email ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $customer->phone ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $customer->address ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $customer->orders_count }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('customers.edit', $customer) }}"
                                           class="text-blue-700 underline">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('customers.destroy', $customer) }}"
                                              onsubmit="return confirm('Delete this customer?')">
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
                                <td colspan="6" class="px-6 py-4 text-center text-gray-600">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>