<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'company_id',
    ];

    /**
     * Get the company that owns the Product.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the inventory record associated with the Product.
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Get all document lines that use this product.
     */
    public function documentLines()
    {
        return $this->hasMany(DocumentLine::class, 'product_id');
    }
}
