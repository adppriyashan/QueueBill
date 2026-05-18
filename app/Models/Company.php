<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;

class Company extends Model
{
    use HasUserstamps;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'created_by',
        'updated_by',
    ];

    public function recurringServices()
    {
        return $this->hasMany(RecurringService::class);
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
