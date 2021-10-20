<?php

namespace App\Traits;

trait FormatDates
{
    public function getEmailVerifiedAtAttribute($value)
    {
        return is_null($value) ? null : \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        return is_null($value) ? null : \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return is_null($value) ? null : \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    /*public function getDeletedAtAttribute($value)
    {
        return is_null($value) ? null : \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }*/
}
