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
        Schema::table('recipes', function (Blueprint $table) {
            // 'change()' sirve para modificar la columna existente
        $table->decimal('total_calories', 8, 2)->default(0)->change();
        
        // Agregamos las nuevas
        $table->decimal('calculated_proteins', 8, 2)->default(0)->after('total_calories');
        $table->decimal('calculated_carbs', 8, 2)->default(0)->after('calculated_proteins');
        $table->decimal('calculated_fats', 8, 2)->default(0)->after('calculated_carbs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['calculated_proteins', 'calculated_carbs', 'calculated_fats']);
        });
    }
};
