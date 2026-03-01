<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khatmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type');           // hifz, review, tilawa
            $table->string('scope');          // full, custom
            $table->string('direction');      // forward, backward
            $table->unsignedSmallInteger('start_page')->default(1);
            $table->unsignedSmallInteger('end_page')->default(604);
            $table->unsignedSmallInteger('total_pages');
            $table->string('planning_method'); // by_duration, by_wird
            $table->unsignedSmallInteger('daily_pages');
            $table->date('start_date');
            $table->date('expected_end_date');
            $table->string('status')->default('active'); // active, paused, completed
            $table->unsignedSmallInteger('current_page')->default(1);
            $table->unsignedSmallInteger('completed_pages')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khatmas');
    }
};
