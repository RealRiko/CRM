<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'company_id',
        'quantity', // Renamed from stock to quantity for clarity
    ];

    /**
     * Get the product associated with this inventory record.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the company associated with this inventory record.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}