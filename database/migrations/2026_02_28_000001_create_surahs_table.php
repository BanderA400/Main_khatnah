<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('number')->unique();
            $table->string('name_arabic');
            $table->unsignedSmallInteger('total_ayahs');
            $table->unsignedSmallInteger('start_page');
            $table->unsignedSmallInteger('end_page');
            $table->unsignedSmallInteger('juz');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};
