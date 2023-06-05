<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use App\Models\Purchase;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function activeSubscriptions()
    {
        return Subscription::where('user_id', $this->id)->where('stripe_status', 'active')->get();
    }

    public function hasActiveSubscriptions()
    {
        $count = $this->activeSubscriptions()->count();
        if($count > 0) {
            return true;
        }
        return false;
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
