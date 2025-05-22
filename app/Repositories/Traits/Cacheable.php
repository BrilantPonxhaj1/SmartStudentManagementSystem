<?php
namespace App\Repositories\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    /** Time-to-live in seconds (override in repo if needed) */
    protected int $cacheTtl = 3600;

    /** Build a tenant-aware tag array */
    protected function tenantTags(string $resource): array
    {
        $user = auth()->user();
        $tags = [];

        // Superadmin sees a global cache:
        if ($user->type === 'superadmin') {
            return [ $resource ];
        }

        // Otherwise, tag by the resolved university & department:
        if ($user->university_id) {
            $tags[] = "univ:{$user->university_id}";
        }
        if ($user->department_id) {
            $tags[] = "dept:{$user->department_id}";
        }

        // Always include the resource name:
        $tags[] = $resource;

        return $tags;
    }

    /** Remember (or fetch) from cache using tags */
    protected function rememberTagged(string $resource, \Closure $callback)
    {
        return Cache::tags($this->tenantTags($resource))
            ->remember($resource, $this->cacheTtl, $callback);
    }

    /** Flush all tagged values for a resource */
    protected function flushTagged(string $resource): void
    {
        Cache::tags($this->tenantTags($resource))->flush();
    }
}
