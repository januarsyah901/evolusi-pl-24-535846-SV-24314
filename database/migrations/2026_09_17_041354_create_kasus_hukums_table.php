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
        Schema::create('kasus_hukums', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kasus')->unique();
            $table->string('judul');
            $table->string('kategori');
            $table->string('status')->default('Penyidikan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasus_hukums');
    }
};
