@php
    $isEditing = $customer !== null;
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
      action="{{ $isEditing ? route('customers.update', $customer) : route('customers.store') }}">
    @csrf

    @if ($isEditing)
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="name" class="block font-medium text-sm text-gray-700">
            Customer name
        </label>

        <input id="name"
               name="name"
               type="text"
               maxlength="150"
               value="{{ old('name', $customer?->name) }}"
               required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="mb-4">
        <label for="email" class="block font-medium text-sm text-gray-700">
            Email
        </label>

        <input id="email"
               name="email"
               type="email"
               maxlength="255"
               value="{{ old('email', $customer?->email) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="mb-4">
        <label for="phone" class="block font-medium text-sm text-gray-700">
            Phone
        </label>

        <input id="phone"
               name="phone"
               type="text"
               maxlength="20"
               value="{{ old('phone', $customer?->phone) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="mb-6">
        <label for="address" class="block font-medium text-sm text-gray-700">
            Address
        </label>

        <textarea id="address"
                  name="address"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address', $customer?->address) }}</textarea>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit"
                class="rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
            {{ $isEditing ? 'Update Customer' : 'Save Customer' }}
        </button>

        <a href="{{ route('customers.index') }}" class="text-gray-600 underline">
            Cancel
        </a>
    </div>
</form>