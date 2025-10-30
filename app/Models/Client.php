<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
class Client extends Model
{
    use HasFactory;

    // $fillable satur masīvu ar laukiem, kurus var aizpildīt masīva vai formas datiem
    // Tas ir drošības mehānisms pret masīva masveida piešķiršanas (mass assignment) uzbrukumiem.
    protected $fillable = [
        'company_id', 
        'name',            
        'email',            
        'phone',            
        'address',          
        'city',            
        'postal_code',     
        'registration_number', 
        'vat_number',       
        'bank',             
        'bank_account',     
    ];

    // Definējam attiecību ar Company modeli
    // Tas nozīmē, ka katrs klients pieder kādai kompānijai
    public function company()
    {
        // belongsTo nozīmē "katrs šis modelis pieder vienam Company modelim"
        return $this->belongsTo(Company::class);
    }
}
