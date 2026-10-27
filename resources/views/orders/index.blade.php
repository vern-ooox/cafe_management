<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Café Orders
        </h2>
    </x-slot>

    <div style="padding-top: 32px; padding-bottom: 32px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex gap-3">
                <a href="{{ route('orders.create') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    Create Order
                </a>

                <a href="{{ route('products.index') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    Products
                </a>

                <a href="{{ route('customers.index') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    Customers
                </a>

                <a href="{{ route('categories.index') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    Categories
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Order number</th>
                            <th class="px-6 py-3 text-left">Customer</th>
                            <th class="px-6 py-3 text-left">Staff member</th>
                            <th class="px-6 py-3 text-left">Total amount</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Payment</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            @php
                                $totalCents = (int) round((float) $order->total_amount * 100);
                                $paidCents = (int) round((float) ($order->payments_sum_amount ?? 0) * 100);
                                $isFullyPaid = $paidCents >= $totalCents;
                            @endphp

                            <tr>
                                <td class="px-6 py-4">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">{{ $order->customer->name }}</td>
                                <td class="px-6 py-4">{{ $order->user->name }}</td>
                                <td class="px-6 py-4">
                                    {{ number_format((float) $order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $isFullyPaid ? 'Paid' : ucfirst($order->status) }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('payments.index', $order) }}"
                                       class="text-blue-700 underline">
                                        View Payments
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-600">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>