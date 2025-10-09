<?php
namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class Document extends Model
   {
       protected $fillable = [
           'type', 'client_id', 'invoice_date', 'delivery_days', 'due_date', 'total', 'status','company_id'
       ];

       protected $casts = [
           'invoice_date' => 'date',
           'due_date' => 'date',
       ];

       public function client()
       {
           return $this->belongsTo(Client::class);
       }

       public function lineItems()
       {
           return $this->hasMany(LineItem::class);
       }
       public function company()
    {
        return $this->belongsTo(Company::class);
    }
   }

