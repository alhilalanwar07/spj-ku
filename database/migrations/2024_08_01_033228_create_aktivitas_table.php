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
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->id();
            // range tanggal, keterangan, tempat
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->default(function () {
                return $this->tanggal_mulai;
            });
            $table->string('tempat')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->text('keterangan')->nullable();

            $table->foreignId('subkegiatan_id')->constrained('subkegiatans')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas');
    }
};
