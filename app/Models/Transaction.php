<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedAtField;

class Transaction extends Model
{
    use HasFactory, CreatedUpdatedAtField;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'status',
        'type',
        'comment',
        'charge_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function sum($user_id)
    {
        return $this->where('user_id', $user_id)->sum('amount');
    }
}
