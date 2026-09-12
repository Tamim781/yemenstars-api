<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'claimed_by',
        'claimed_at',
        'customer_name',
        'customer_phone',
        'address',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'transfer_reference',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'claimed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claimant()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }
}
