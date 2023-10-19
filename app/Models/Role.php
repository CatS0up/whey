<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\RoleBuilder;
use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;
    use Sluggable;

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return RoleBuilder<Role>
     */
    public function newEloquentBuilder($query): RoleBuilder
    {
        return new RoleBuilder($query);
    }

    public function sluggableField(): string
    {
        return 'name';
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
