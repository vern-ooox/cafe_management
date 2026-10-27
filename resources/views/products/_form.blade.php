@php
    $isEditing = $product !== null;
    $selectedAvailability = old(
        'is_available',
        $product ? ($product->is_available ? '1' : '0') : '1'
    );
@endphp

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

<form method="POST"
      action="{{ $isEditing ? route('products.update', $product) : route('products.store') }}">
    @csrf

    @if ($isEditing)
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="category_id" class="block font-medium text-sm text-gray-700">
            Category
        </label>

        <select id="category_id"
                name="category_id"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Choose a category</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $product?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="name" class="block font-medium text-sm text-gray-700">
            Product name
        </label>

        <input id="name"
               name="name"
               type="text"
               maxlength="150"
               value="{{ old('name', $product?->name) }}"
               required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="mb-4">
        <label for="description" class="block font-medium text-sm text-gray-700">
            Description
        </label>

        <textarea id="description"
                  name="description"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $product?->description) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="price" class="block font-medium text-sm text-gray-700">
            Price
        </label>

        <input id="price"
               name="price"
               type="number"
               min="0"
               step="0.01"
               value="{{ old('price', $product?->price) }}"
               required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="mb-4">
        <label for="stock_quantity" class="block font-medium text-sm text-gray-700">
            Stock quantity
        </label>

        <input id="stock_quantity"
               name="stock_quantity"
               type="number"
               min="0"
               step="1"
               value="{{ old('stock_quantity', $product?->stock_quantity ?? 0) }}"
               required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="mb-6">
        <label for="is_available" class="block font-medium text-sm text-gray-700">
            Availability
        </label>

        <select id="is_available"
                name="is_available"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="1" @selected($selectedAvailability == '1')>
                Available
            </option>
            <option value="0" @selected($selectedAvailability == '0')>
                Unavailable
            </option>
        </select>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit"
                class="rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
            {{ $isEditing ? 'Update Product' : 'Save Product' }}
        </button>

        <a href="{{ route('products.index') }}" class="text-gray-600 underline">
            Cancel
        </a>
    </div>
</form>