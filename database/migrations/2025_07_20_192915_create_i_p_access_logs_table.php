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
        Schema::create('i_p_access_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_url', 500);
            $table->string('http_method', 10);
            $table->boolean('is_blocked')->default(false);
            $table->string('location')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('ip_address');
            $table->index('created_at');
            $table->index('is_blocked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_p_access_logs');
    }
};
