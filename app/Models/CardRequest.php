<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedAtField;
class CardRequest extends Model

{
    use HasFactory, CreatedUpdatedAtField;

    protected $table = 'card_requests';

    protected $fillable = [
        'user_id',
        'amount',
        'cost',
        'quantity',
        'status',
        'package',
        'comment',
    ];

    protected $appends = [
        'limit_reached',
        'number_of_cards'
    ];

    // check if the quantity of cards requested is equal to the quantity of cards created.
    public function getLimitReachedAttribute()
    {
        return $this->quantity >= $this->cards()->count();
    }

    public function getNumberOfCardsAttribute()
    {
        return $this->cards()->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
