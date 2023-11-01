<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\Role
 * @extends Builder<TModelClass>
 */
class RoleBuilder extends Builder
{
    /** Builder methods - start */
    public function whereSlugIn(array $slugs): self
    {
        return $this->whereIn('slug', $slugs);
    }

    public function whereHasPermissionBySlug(string $slug): self
    {
        return $this->whereRelation('permissions', 'slug', $slug);
    }
    /** Builder methods - end */
}
