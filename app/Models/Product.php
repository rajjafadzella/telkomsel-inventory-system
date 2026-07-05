<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'stock',
        'location',
        'condition',
        'image'
    ];

    // Relasi balik ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}