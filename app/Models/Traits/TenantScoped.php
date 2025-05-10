<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;

trait TenantScoped
{
    protected static function bootTenantScoped(): void
    {
        static::addGlobalScope(new TenantScope);
    }
}
