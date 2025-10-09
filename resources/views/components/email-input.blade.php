@props(['id', 'value' => '', 'type' => 'email', 'name', 'autocomplete' => 'email', 'required' => false])

<div {{ $attributes->merge(['class' => 'mt-2']) }}>
    <input
        {{ $attributes->merge(['class' => 'block w-full border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-sm']) }}
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        autocomplete="{{ $autocomplete }}"
        @if($required) required @endif
    >
    @error($name)
        <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
    @enderror
</div>