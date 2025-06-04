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
        Schema::table('settings', function (Blueprint $table) {
            $table->text('deskripsi_desa')->nullable();
            $table->string('alamat_desa')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->json('social_media')->nullable(); // Menyimpan data seperti: {"instagram": "...", "facebook": "..."}
            $table->string('kode_pos')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->json('koordinat_lokasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
