<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the new inventories table
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            // Foreign key to link to the product definition
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Foreign key to link to the company (useful for faster querying)
            $table->foreignId('company_id')->constrained()->onDelete('cascade');

            // The actual stock/quantity level for the product
            $table->integer('quantity')->default(0);

            $table->timestamps();

            // Ensure a product can only have one inventory record per company
            $table->unique(['product_id', 'company_id']);
        });

        // 2. Optionally, remove the 'stock' column from the 'products' table,
        // as inventory is now stored separately.
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the 'stock' column if it was dropped
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0);
            }
        });

        // Drop the new inventories table
        Schema::dropIfExists('inventories');
    }
};
