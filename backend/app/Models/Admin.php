<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'username', 'email', 'password_hash', 'full_name', 'role',
    ];

    protected $hidden = [
        'password_hash',
    ];
}
