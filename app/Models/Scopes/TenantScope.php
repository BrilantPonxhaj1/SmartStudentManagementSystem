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

        // 3) Scope by university_id
        $builder->where(
            $model->getTable() . '.university_id',
            $user->university_id
        );

        // 4) Optionally scope by department_id
        if ($user->department_id) {
            $builder->where(
                $model->getTable() . '.department_id',
                $user->department_id
            );
        }
    }
}
