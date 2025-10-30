<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    // Norāda, kuri lauki ir atļauti masveida aizpildīšanai (mass assignment)
    protected $fillable = [
        'type',          // Dokumenta tips (piemēram: estimate, sales_order, sales_invoice)
        'client_id',     // Saistītā klienta ID
        'invoice_date',  // Rēķina datums
        'delivery_days', // Piegādes dienu skaits
        'due_date',      // Maksājuma termiņš
        'total',         // Kopējā summa
        'status',        // Dokumenta statuss (piemēram: draft, paid, sent u.c.)
        'company_id'     // Uzņēmuma ID, kuram dokuments pieder
    ];

    // Norāda, kuri lauki automātiski jākonvertē uz datuma tipiem
    protected $casts = [
        'invoice_date' => 'date', // Laravel automātiski apstrādā kā Carbon datuma objektu
        'due_date' => 'date',     // Tas pats arī ar termiņa datumu
    ];

    // Attiecība: viens dokuments pieder vienam klientam
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Attiecība: vienam dokumentam var būt vairākas rindu vienības (line items)
    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }

    // Attiecība: dokuments pieder konkrētam uzņēmumam
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
