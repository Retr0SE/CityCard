<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Вкажіть тут точну назву вашої таблиці цін (ticket_prices або ticket_price)
        Schema::table('ticket_prices', function (Blueprint $table) {

            $table->foreignId('ticket_type_id')
                  ->nullable()
                  ->after('id') 
                  ->constrained('ticket_types')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_prices', function (Blueprint $table) {
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn('ticket_type_id');
        });
    }
};