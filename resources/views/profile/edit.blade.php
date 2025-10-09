@extends('layouts.app')

@section('title', 'Profile - ' . config('app.name', 'Inventory Management'))

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="card animate-slide-up">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card animate-slide-up">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form') <!-- Labots uz pareizo daļējo skatu -->
                </div>
            </div>

            <div class="card animate-slide-up">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection