<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city',
        'favorite',
        'send_forecast',
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
