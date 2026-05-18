<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;

class RecurringService extends Model
{
    use HasUserstamps;

    protected $fillable = [
        'company_id',
        'invoice_structure_template_id',
        'name',
        'from_date',
        'to_date',
        'recurring_cadence',
        'base_cost',
        'invoice_includes',
        'next_billing_date',
        'google_drive_path',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'next_billing_date' => 'date',
        'base_cost' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoiceStructureTemplate()
    {
        return $this->belongsTo(InvoiceStructureTemplate::class, 'invoice_structure_template_id');
    }

    public function pendingInvoiceLines()
    {
        return $this->hasMany(PendingInvoiceLine::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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
