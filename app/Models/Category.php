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
            $fixed = str_replace('/storage/storage/', '/storage/', $value);
            if (\Illuminate\Support\Str::startsWith($fixed, 'http://yemenstars-api-production.up.railway.app')) {
                $fixed = preg_replace('/^http:/', 'https:', $fixed);
            }
            return $fixed;
        }

        $clean = ltrim($value, '/');
        if (\Illuminate\Support\Str::startsWith($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }

        return 'https://yemenstars-api-production.up.railway.app/storage/' . ltrim($clean, '/');
    }
}
