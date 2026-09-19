<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'action_type',
        'action_id',
        'is_active',
        'starts_at',
        'ends_at',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected $appends = ['resolved_image_url'];

    public function getResolvedImageUrlAttribute(): ?string
    {
        $value = $this->attributes['image_url'] ?? null;

        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
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
