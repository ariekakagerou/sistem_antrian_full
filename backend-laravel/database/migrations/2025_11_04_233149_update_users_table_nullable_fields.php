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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->change();
            $table->string('username')->nullable()->change();
            $table->string('fullname')->nullable()->change();
            $table->string('nomor_hp')->nullable()->change();
            $table->string('whatsapp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable(false)->change();
            $table->string('username')->nullable(false)->change();
            $table->string('fullname')->nullable(false)->change();
            $table->string('nomor_hp')->nullable(false)->change();
            $table->string('whatsapp')->nullable(false)->change();
        });
    }
};
