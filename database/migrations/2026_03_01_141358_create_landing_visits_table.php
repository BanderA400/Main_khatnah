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
        Schema::create('landing_visits', function (Blueprint $table) {
            $table->id();
            $table->string('fingerprint', 64);
            $table->date('visited_on');
            $table->boolean('is_unique')->default(false);
            $table->timestamps();

            $table->index('visited_on');
            $table->index(['visited_on', 'fingerprint']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_visits');
    }
};
