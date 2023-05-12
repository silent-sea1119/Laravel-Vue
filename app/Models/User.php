<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\CreatedUpdatedAtField;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CreatedUpdatedAtField;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'verified_by_admin_at',
        'verified_by',
        'password',
        'gender',
        'verification_token',
        'user_role',
        'suspend_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_by_admin_at' => 'datetime',
    ];

    protected $appends = ['name', 'balance', 'num_of_cards'];

    public function getNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getBalanceAttribute() {
        return $this->transactions()->where('status', 'completed')->sum('amount');
    }

    public function getNumOfCardsAttribute() {
        return $this->cards()->count();
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function cardRequests()
    {
        return $this->hasMany(CardRequest::class);
    }
    
}
