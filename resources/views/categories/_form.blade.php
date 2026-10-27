@php
    $isEditing = $category !== null;
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
      action="{{ $isEditing ? route('categories.update', $category) : route('categories.store') }}">
    @csrf

    @if ($isEditing)
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="name" class="block font-medium text-sm text-gray-700">
            Category name
        </label>

        <input id="name"
               name="name"
               type="text"
               maxlength="100"
               value="{{ old('name', $category?->name) }}"
               required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="mb-6">
        <label for="description" class="block font-medium text-sm text-gray-700">
            Description
        </label>

        <textarea id="description"
                  name="description"
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $category?->description) }}</textarea>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit"
                class="rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
            {{ $isEditing ? 'Update Category' : 'Save Category' }}
        </button>

        <a href="{{ route('categories.index') }}" class="text-gray-600 underline">
            Cancel
        </a>
    </div>
</form>