<?php

namespace App\Traits;

use App\Models\User;

trait UserActivity
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'name');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by', 'name');
    }

    public function getCreatedByAttribute($value)
    {
        return User::findOrFail($value)->name;
    }

    public function getUpdatedByAttribute($value)
    {
        return User::findOrFail($value)->name;
    }
}
