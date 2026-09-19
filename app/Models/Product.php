<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'old_price',
        'image_url',
        'is_available',
        'is_featured',
        'is_daily_special',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            $fixed = str_replace('/storage/storage/', '/storage/', $value);
            if (Str::startsWith($fixed, 'http://yemenstars-api-production.up.railway.app')) {
                $fixed = preg_replace('/^http:/', 'https:', $fixed);
            }
            return $fixed;
        }

        $clean = ltrim($value, '/');
        if (Str::startsWith($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }

        return 'https://yemenstars-api-production.up.railway.app/storage/' . ltrim($clean, '/');
    }
}
