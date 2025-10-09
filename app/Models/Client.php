<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class Client extends Model
// Definē Client klasi, kas paplašina Eloquent Model klasi, tādējādi mantojot visas ORM iespējas
{
    protected $fillable = ['name', 'email', 'phone','company_id'];
    // $fillable norāda, kuri lauki ir masīvi pieejami "mass assignment"
    // Tas palīdz aizsargāt pret masīvu datu neaizsargātu piešķiršanu (Mass Assignment Vulnerability)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
