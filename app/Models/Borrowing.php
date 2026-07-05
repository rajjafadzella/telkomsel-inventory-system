<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'borrow_date', 'return_date', 'status']; // [cite: 68, 70, 71, 72]

    // Relasi ke User (Peminjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail Peminjaman
    public function details()
    {
        return $this->hasMany(BorrowingDetail::class);
    }
}