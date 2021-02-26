<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class BlameableObserver
{
    public function creating(Model $model)
    {
        $model->created_by = auth()->user()->id;
        $model->updated_by = auth()->user()->id;
    }

    public function updating(Model $model)
    {
        $model->updated_by = auth()->user()->id;
    }
}
