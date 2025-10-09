   <?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up()
       {
           Schema::create('documents', function (Blueprint $table) {
               $table->id();
               $table->string('type');
               $table->unsignedBigInteger('client_id');
               $table->date('invoice_date'); 
               $table->integer('delivery_days');
               $table->date('due_date')->nullable();
               $table->decimal('total', 8, 2);
               $table->string('status');
               $table->timestamps();

               $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
           });
       }

       public function down()
       {
           Schema::dropIfExists('documents');
       }
   };