<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('from_unit_id')->constrained('units')->cascadeOnDelete();
            $table->foreignId('to_unit_id')->constrained('units')->cascadeOnDelete();
            $table->decimal('conversion_factor', 10, 4);
            $table->timestamps();

            $table->unique(['category_id', 'from_unit_id', 'to_unit_id'], 'unique_conversion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_conversions');
    }
};
