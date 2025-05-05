<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check()) {
            // Always include university filter
            $builder->where($model->getTable().'.university_id', auth()->user()->university_id);

            // Optionally include department filter if the user is restricted to one department
            if (!is_null(auth()->user()->department_id)) {
                $builder->where($model->getTable().'.department_id', auth()->user()->department_id);
            }
        }
    }
}
