<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            // Прив'язуємо картку до типу квитка
            $table->foreignId('ticket_type_id')
                  ->nullable()
                  ->constrained('ticket_types')
                  ->nullOnDelete(); // Якщо тип квитка видалять, картка просто стане NULL, а не видалиться
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn('ticket_type_id');
        });
    }
};