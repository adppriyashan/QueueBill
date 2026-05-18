<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'invoice_id',
        'sender',
        'recipient',
        'subject',
        'body',
        'version',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
