<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This adds all the new company detail columns to the 'companies' table.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // New columns for invoicing/admin settings
            $table->string('registration_number', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('vat_number', 50)->nullable();
            $table->string('footer_contacts', 500)->nullable();
            $table->string('logo_path')->nullable();
            // Note: 'country', 'name', and 'monthly_goal' are assumed to already exist.
        });
    }

    /**
     * Reverse the migrations.
     * This removes the new columns if the migration is rolled back.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'registration_number', 
                'address', 
                'city', 
                'postal_code', 
                'bank_name', 
                'account_number', 
                'vat_number', 
                'footer_contacts', 
                'logo_path'
            ]);
        });
    }
};