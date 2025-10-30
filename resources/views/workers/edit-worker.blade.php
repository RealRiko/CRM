@extends('layouts.app')

@section('title', 'Edit Worker - ' . config('app.name', 'Inventory Management'))

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl max-w-4xl mx-auto border border-gray-100 dark:border-gray-700">
        <h1 class="text-3xl font-extrabold text-center mb-8 text-amber-600 dark:text-amber-400 border-b border-gray-200 dark:border-gray-700 pb-4">
            Edit Worker: {{ $worker->name }} {{ $worker->surname }}
        </h1>

        {{-- Include the partial form with $worker --}}
        @include('workers._form_workers', ['worker' => $worker])
    </div>
</div>
@endsection
