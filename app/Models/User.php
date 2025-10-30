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

    /**
     * ✅ Relationship: user belongs to one company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * ✅ Relationship: a company admin can have many employees (users)
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    /**
     * ✅ Check if the user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
