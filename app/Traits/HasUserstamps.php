<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasUserstamps
{
    public static function bootHasUserstamps()
    {
        static::creating(function ($model) {
            if (!$model->created_by && Auth::check()) {
                $model->created_by = Auth::id();
            }
            if (!$model->updated_by && Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
