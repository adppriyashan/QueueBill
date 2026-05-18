<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;

class PendingInvoiceLine extends Model
{
    use HasUserstamps;

    protected $fillable = [
        'recurring_service_id',
        'description',
        'amount',
        'billing_status',
        'invoice_id',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function recurringService()
    {
        return $this->belongsTo(RecurringService::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
