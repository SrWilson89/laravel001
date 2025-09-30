<?php

// database/migrations/XXXX_XX_XX_XXXXXX_add_profile_photo_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Añade una columna para la ruta de la foto, puede ser nula.
            $table->string('profile_photo_path', 2048)->nullable()->after('email'); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });
    }
};
