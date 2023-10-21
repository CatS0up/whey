<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class PermissionsServiceProvider extends ServiceProvider
{
    public const PERMISSIONS_CACHE_TTL = 1440;

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // It's not an elegant way, but it works ;)
        try {
            if ( ! Schema::hasTable('permissions')) {
                return;
            }

            $permissions = Cache::remember(
                key: 'permissions',
                ttl: self::PERMISSIONS_CACHE_TTL,
                callback: fn (): Collection => Permission::all()
            );

            foreach ($permissions as $permission) {
                Gate::define(
                    ability: $permission->slug,
                    callback: fn (User $user): bool => $user->hasPermissionToBySlug($permission->slug),
                );
            }
        } catch (Throwable $e) {
            report($e);
        }

        Blade::directive('role', fn (string $slug) => "<?php if(auth()->check() && auth()->user()->hasRoleBySlug({$slug})) : ?>");

        Blade::directive('endrole', fn (string $slug) => "<?php endif; ?>");
    }
}
