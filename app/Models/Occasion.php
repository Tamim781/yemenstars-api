<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occasion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'occasion_date', 'notes'];

    protected $casts = ['occasion_date' => 'date'];
}
