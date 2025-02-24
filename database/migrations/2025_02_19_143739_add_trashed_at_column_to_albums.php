<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable(); // Menambahkan kolom 'trashed_at' dengan tipe timestamp
        });
    }

    public function down()
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn('deleted_at'); // Menghapus kolom 'trashed_at'
        });
    }
};
