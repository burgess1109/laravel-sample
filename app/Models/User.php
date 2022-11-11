<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = ['account', 'password', 'email', 'role_id'];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
