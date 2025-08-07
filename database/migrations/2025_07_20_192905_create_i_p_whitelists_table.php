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
        Schema::create('i_p_whitelists', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45); // Support IPv6
            $table->string('description');
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

            $table->foreign('added_by')->references('id')->on('users');
            $table->unique('ip_address');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_p_whitelists');
    }
};
