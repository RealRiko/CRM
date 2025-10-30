@extends('layouts.app')

@section('title', 'Edit Document - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{--
            Includes the reusable form partial.
            - $document is passed, so it defaults to 'Edit' mode.
            - $clients and $products must be passed from the controller.
        --}}
        @include('documents._document_form', ['document' => $document])

    </div>
@endsection