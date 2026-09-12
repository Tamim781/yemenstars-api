<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'instructions', 'account_number',
        'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
