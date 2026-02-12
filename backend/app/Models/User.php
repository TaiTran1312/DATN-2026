<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Comment;
use App\Models\Cart;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'user_id';  // ← Khai báo primary key là user_id

    public $incrementing = true;        // Nếu là auto-increment

    protected $keyType = 'int';         // Nếu là integer

    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'is_active',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    // Nếu muốn tự động hash password khi set
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
 /**
     * Kiểm tra user có active không
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Relationships
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'user_id');
    }

}
