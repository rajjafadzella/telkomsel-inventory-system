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
    Schema::create('borrowings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Nama Peminjam (Relasi ke Users)
        $table->date('borrow_date'); // Tanggal Pinjam
        $table->date('return_date')->nullable(); // Tanggal Kembali
        $table->enum('status', ['Dipinjam', 'Dikembalikan'])->default('Dipinjam'); // Status Peminjaman
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
