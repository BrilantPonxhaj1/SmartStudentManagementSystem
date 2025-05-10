<?php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Superadmins see everything
        if ($user->type === 'superadmin') {
            return;
        }

        // Determine tenant IDs from the appropriate profile
        if ($user->type === 'student') {
            $profile = $user->studentProfile;
        } elseif ($user->type === 'professor') {
            $profile = $user->professorProfile;
        } else {
            // No profile? bail out (or you could abort)
            return;
        }

        // If somehow there is no profile row, don’t scope at all
        if (! $profile) {
            return;
        }

        // Scope by university
        $builder->where(
            $model->getTable() . '.university_id',
            $profile->university_id
        );

        // Scope by department if present
        if (! is_null($profile->department_id)) {
            $builder->where(
                $model->getTable() . '.department_id',
                $profile->department_id
            );
        }
    }
}
