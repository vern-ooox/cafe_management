<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Record Payment
        </h2>
    </x-slot>

    <div style="padding-top: 32px; padding-bottom: 32px;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="mb-6 space-y-2">
                    <p>
                        <strong>Order:</strong>
                        {{ $order->order_number }}
                    </p>

                    <p>
                        <strong>Order total:</strong>
                        {{ number_format((float) $order->total_amount, 2) }}
                    </p>

                    <p>
                        <strong>Already paid:</strong>
                        {{ number_format($amountPaid, 2) }}
                    </p>

                    <p>
                        <strong>Remaining balance:</strong>
                        {{ number_format($remainingBalance, 2) }}
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($remainingBalance <= 0)
                    <p class="mb-4 text-green-700">
                        This order has been fully paid.
                    </p>
                @else
                    <form method="POST" action="{{ route('payments.store', $order) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="payment_reference"
                                   class="block font-medium text-sm text-gray-700">
                                Payment reference
                            </label>

                            <input id="payment_reference"
                                   name="payment_reference"
                                   type="text"
                                   maxlength="100"
                                   value="{{ old('payment_reference') }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            @error('payment_reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_method"
                                   class="block font-medium text-sm text-gray-700">
                                Payment method
                            </label>

                            <select id="payment_method"
                                    name="payment_method"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Choose a method</option>
                                <option value="cash" @selected(old('payment_method') === 'cash')>
                                    Cash
                                </option>
                                <option value="card" @selected(old('payment_method') === 'card')>
                                    Card
                                </option>
                                <option value="gcash" @selected(old('payment_method') === 'gcash')>
                                    GCash
                                </option>
                                <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>
                                    Bank transfer
                                </option>
                            </select>

                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="amount"
                                   class="block font-medium text-sm text-gray-700">
                                Amount
                            </label>

                            <input id="amount"
                                   name="amount"
                                   type="number"
                                   min="0.01"
                                   max="{{ number_format($remainingBalance, 2, '.', '') }}"
                                   step="0.01"
                                   value="{{ old('amount') }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="paid_at"
                                   class="block font-medium text-sm text-gray-700">
                                Payment date and time
                            </label>

                            <input id="paid_at"
                                   name="paid_at"
                                   type="datetime-local"
                                   value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            @error('paid_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                                Save Payment
                            </button>

                            <a href="{{ route('orders.index') }}"
                               class="text-gray-600 underline">
                                Cancel
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>