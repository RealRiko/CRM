   <?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('document_line_items', function (Blueprint $table) {
               $table->id();
               $table->foreignId('document_id')->constrained()->onDelete('cascade');
               $table->foreignId('product_id')->constrained()->onDelete('cascade');
               $table->integer('quantity');
               $table->decimal('price', 8, 2);
               $table->decimal('subtotal', 8, 2);
               $table->timestamps();
           });
       }

       public function down(): void
       {
           Schema::dropIfExists('document_line_items');
       }
   };