@extends('layouts.app')

@section('title', 'Create Product - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Includes the reusable form partial. $product will be null/undefined, triggering 'Create' mode. --}}
        @include('products._product_form')
    </div>
@endsection