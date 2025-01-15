<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAlbumIdNullableOnPhotosTable extends Migration
{
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->nullable()->change(); // Boleh null
        });
    }

    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->nullable(false)->change();
        });
    }
}

