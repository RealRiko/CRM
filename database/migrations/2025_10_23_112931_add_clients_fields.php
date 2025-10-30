<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address')->comment('Pilsēta');
            $table->string('postal_code')->nullable()->after('city')->comment('Pasta indekss');
            $table->string('registration_number')->nullable()->after('postal_code')->default('N/A')->comment('Reģistrācijas numurs');
            $table->string('vat_number')->nullable()->after('registration_number')->default('N/A')->comment('PVN numurs');
            $table->string('bank')->nullable()->after('vat_number')->default('N/A')->comment('Banka');
            $table->string('bank_account')->nullable()->after('bank')->default('N/A')->comment('Bankas konta numurs');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'postal_code',
                'registration_number',
                'vat_number',
                'bank',
                'bank_account',
            ]);
        });
    }
};
