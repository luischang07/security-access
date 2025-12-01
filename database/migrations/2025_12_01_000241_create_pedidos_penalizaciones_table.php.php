<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedido_penalizaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('folio_pedido')->unique();

            $table->decimal('monto', 10, 2);

            $table->timestamps();

            // Definición de la llave foránea
            $table->foreign('folio_pedido')
                ->references('folio_pedido')
                ->on('pedidos')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_penalizaciones');
    }
};