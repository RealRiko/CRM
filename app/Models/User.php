<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'company_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

public function company()
{
    return $this->belongsTo(Company::class, 'company_id', 'id');
}
    public function employees()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}