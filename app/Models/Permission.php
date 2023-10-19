<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\PermissionBuilder;
use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;
    use Sluggable;

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return PermissionBuilder<Permission>
     */
    public function newEloquentBuilder($query): PermissionBuilder
    {
        return new PermissionBuilder($query);
    }

    public function sluggableField(): string
    {
        return 'name';
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
