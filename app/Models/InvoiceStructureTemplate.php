<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Illuminate\Support\Str;

class InvoiceStructureTemplate extends Model
{
    use HasUserstamps;

    protected $fillable = [
        'title',
        'slug',
        'sender_email',
        'status',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($template) {
            if (empty($template->slug)) {
                $template->slug = Str::slug($template->title);
            }
        });
    }

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
