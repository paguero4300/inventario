<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('quantity', 10, 2);
            $table->decimal('quantity_base', 10, 2)->nullable();
            $table->enum('entry_type', ['entrada', 'salida', 'ajuste']);
            $table->text('notes')->nullable();
            $table->timestamp('entry_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_entries');
    }
};
