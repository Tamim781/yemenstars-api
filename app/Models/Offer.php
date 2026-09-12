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
            return $value;
        }

        $host = request()->getHost();
        $port = request()->getPort();
        $base = 'http://' . $host . (($port && !in_array($port, [80, 443], true)) ? ':' . $port : '');

        return rtrim($base, '/') . '/storage/' . ltrim($value, '/');
    }
}
