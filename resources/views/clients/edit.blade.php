@extends('layouts.app')

@section('title', 'Edit Client - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Includes the reusable form partial, passing $client to activate 'Edit' mode. --}}
        @include('clients._client_form', ['client' => $client])
    </div>
@endsection
