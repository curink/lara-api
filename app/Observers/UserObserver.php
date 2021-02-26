<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserObserver
{
    public function creating(Model $model)
    {
        $role = Role::all()->last()->name;
        $model->syncRoles($role);
    }
}
