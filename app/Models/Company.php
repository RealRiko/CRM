<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Ensure all new fields needed for company details are listed here.
     */
    protected $fillable = [
        'name',
        'registration_number', // New field for company registration/ID number
        'address',
        'city',
        'postal_code',
        'country', // Retaining country from your version
        'bank_name',
        'account_number',
        'vat_number',
        'footer_contacts',
        'logo_path',
        'monthly_goal',
        'owner_id', 
    ];

    /**
     * Get the users associated with the company.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }
    
    // You might also want a relationship for the company owner (optional, depending on your setup)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
