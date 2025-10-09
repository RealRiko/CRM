   @extends('layouts.app')

   @section('title', 'Dashboard - ' . config('app.name', 'Inventory Management'))

   @section('content')
       <div class="container mx-auto py-8">
           <h1 class="text-3xl font-bold text-foreground mb-6">Dashboard</h1>
           <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
               <div class="card animate-slide-up">
                   <h3 class="text-xl font-semibold mb-4">Products</h3>
                   <p class="text-3xl">{{ $productCount ?? 0 }}</p>
               </div>
               <div class="card animate-slide-up">
                   <h3 class="text-xl font-semibold mb-4">Clients</h3>
                   <p class="text-3xl">{{ $clientCount ?? 0 }}</p>
               </div>
               <div class="card animate-slide-up">
                   <h3 class="text-xl font-semibold mb-4">Documents</h3>
                   <p class="text-3xl">{{ $documentCount ?? 0 }}</p>
               </div>
           </div>
       </div>
   @endsection