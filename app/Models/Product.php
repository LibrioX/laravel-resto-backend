<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //fillable
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'price',
        'stock',
        'status',
        'is_favorite'
    ];

    //relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
