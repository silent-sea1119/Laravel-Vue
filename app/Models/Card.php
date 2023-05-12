<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedAtField;

class Card extends Model
{
    use HasFactory, CreatedUpdatedAtField;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'card_type',
        'currency',
        'first_name',
        'last_name',
        'card_number',
        'cvc',
        'expire_month',
        'expire_year',
        'address',
        'city',
        'state',
        'zip',
        'card_request_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function cardRequest()
    {
        return $this->belongsTo(CardRequest::class);
    }
}
