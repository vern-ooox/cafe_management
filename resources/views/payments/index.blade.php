<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Payment History
        </h2>
    </x-slot>

    <div style="padding-top: 32px; padding-bottom: 32px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 rounded-lg bg-white p-6 shadow-sm">
                <p><strong>Order:</strong> {{ $order->order_number }}</p>
                <p><strong>Order total:</strong> {{ number_format((float) $order->total_amount, 2) }}</p>
                <p><strong>Paid:</strong> {{ number_format($amountPaid, 2) }}</p>
                <p><strong>Remaining balance:</strong> {{ number_format($remainingBalance, 2) }}</p>
            </div>

            <div class="mb-4 flex gap-3">
                @if ($remainingBalance > 0)
                    <a href="{{ route('payments.create', $order) }}"
                       class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                        Record Payment
                    </a>
                @else
                    <span class="inline-block rounded-md bg-green-100 px-4 py-2 text-green-800">
                        Paid in full
                    </span>
                @endif

                <a href="{{ route('orders.index') }}"
                   class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                    Back to Orders
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Payment reference</th>
                            <th class="px-6 py-3 text-left">Method</th>
                            <th class="px-6 py-3 text-left">Amount</th>
                            <th class="px-6 py-3 text-left">Paid at</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($payments as $payment)
                            <tr>
                                <td class="px-6 py-4">
                                    {{ $payment->payment_reference }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ number_format((float) $payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $payment->paid_at->format('M j, Y g:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-600">
                                    No payments recorded for this order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>