<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_name',
        'logo_url',
        'phone',
        'whatsapp_url',
        'instagram_url',
        'facebook_url',
        'address',
        'working_hours',
        'delivery_fee',
        'jaib_account',
        'jawali_account',
        'kuraimi_account',
    ];
}

