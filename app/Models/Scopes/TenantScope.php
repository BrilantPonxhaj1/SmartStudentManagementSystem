<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        if ($user->type === 'superadmin') {
            return;
        }
        $builder->where($model->getTable().'.university_id', $user->university_id);

        if (!is_null($user->department_id)) {
            $builder->where($model->getTable().'.department_id', $user->department_id);
        }
    }
}
