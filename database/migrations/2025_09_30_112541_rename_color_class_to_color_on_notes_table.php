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
        // Renombra la columna 'color_class' a 'color'
        Schema::table('notes', function (Blueprint $table) {
            $table->renameColumn('color_class', 'color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Renombra la columna 'color' de vuelta a 'color_class'
        Schema::table('notes', function (Blueprint $table) {
            $table->renameColumn('color', 'color_class');
        });
    }
};
