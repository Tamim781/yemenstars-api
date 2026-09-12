<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_url', 'sort_order'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return url('storage/' . ltrim($value, '/'));
    }
}
