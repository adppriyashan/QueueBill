<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleDriveLog extends Model
{
    protected $table = 'google_drive_logs';

    protected $fillable = [
        'invoice_id',
        'google_drive_path',
        'version',
        'status',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Dynamic Simulated Virtual Attributes
    public function getFileNameAttribute()
    {
        $num = $this->invoice?->invoice_number ?? 'QB-MOCK';
        return "{$num}_v{$this->version}.pdf";
    }

    public function getDetailsAttribute()
    {
        return "Statement version {$this->version} synchronized cleanly to target Google Drive folder '{$this->google_drive_path}'.";
    }
}
