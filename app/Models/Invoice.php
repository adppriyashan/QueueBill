<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;

class Invoice extends Model
{
    use HasUserstamps;

    protected $fillable = [
        'invoice_number',
        'company_id',
        'invoice_structure_template_id',
        'recurring_service_id',
        'period_from',
        'period_to',
        'issue_date',
        'due_date',
        'subtotal',
        'total',
        'version',
        'google_drive_path',
        'uploaded_to_drive_at',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'period_from' => 'date',
        'period_to' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'uploaded_to_drive_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoiceStructureTemplate()
    {
        return $this->belongsTo(InvoiceStructureTemplate::class, 'invoice_structure_template_id');
    }

    public function recurringService()
    {
        return $this->belongsTo(RecurringService::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function pendingInvoiceLines()
    {
        return $this->hasMany(PendingInvoiceLine::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    public function googleDriveLogs()
    {
        return $this->hasMany(GoogleDriveLog::class);
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
