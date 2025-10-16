@extends('layouts.app')

@section('title', 'Edit Product - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- 
            This file uses the reusable _product_form partial.
            By passing ['product' => $product], we ensure the partial knows 
            to display the "Edit Product" header, pre-fill the form, 
            and set the action route to 'products.update' using the PATCH method.
        --}}
        @include('products._product_form', ['product' => $product])
    </div>
@endsection
