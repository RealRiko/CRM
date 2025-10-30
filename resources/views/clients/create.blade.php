@extends('layouts.app')

@section('title', 'Create Client - ' . config('app.name', 'Inventory Management'))

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('clients._client_form')
</div>
@endsection
