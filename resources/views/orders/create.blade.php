<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Order
        </h2>
    </x-slot>

    <div style="padding-top: 32px; padding-bottom: 32px;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

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

                @if ($customers->isEmpty())
                    <p class="text-red-700">
                        You need to add a customer before creating an order.
                    </p>
                @elseif ($products->isEmpty())
                    <p class="text-red-700">
                        There are no available products in stock.
                    </p>
                @else
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="customer_id"
                                   class="block font-medium text-sm text-gray-700">
                                Customer
                            </label>

                            <select id="customer_id"
                                    name="customer_id"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Choose a customer</option>

                                @foreach ($customers as $customer)
                                    <option
                                        value="{{ $customer->id }}"
                                        @selected(old('customer_id') == $customer->id)
                                    >
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <h3 class="mb-3 font-semibold text-gray-800">
                            Products
                        </h3>

                        <p class="mb-4 text-sm text-gray-600">
                            Select at least one product. Leave unused rows blank.
                        </p>

                        @for ($i = 0; $i < 5; $i++)
                            <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="sm:col-span-2">
                                    <label for="product_{{ $i }}"
                                           class="block font-medium text-sm text-gray-700">
                                        Product {{ $i + 1 }}
                                    </label>

                                    <select id="product_{{ $i }}"
                                            name="items[{{ $i }}][product_id]"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Do not add a product</option>

                                        @foreach ($products as $product)
                                            <option
                                                value="{{ $product->id }}"
                                                @selected(old("items.$i.product_id") == $product->id)
                                            >
                                                {{ $product->name }}
                                                — {{ number_format((float) $product->price, 2) }}
                                                (stock: {{ $product->stock_quantity }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="quantity_{{ $i }}"
                                           class="block font-medium text-sm text-gray-700">
                                        Quantity
                                    </label>

                                    <input id="quantity_{{ $i }}"
                                           type="number"
                                           name="items[{{ $i }}][quantity]"
                                           value="{{ old("items.$i.quantity") }}"
                                           min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        @endfor

                        <div class="mt-6 flex items-center gap-4">
                            <button type="submit"
                                    class="rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                                Save Order
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