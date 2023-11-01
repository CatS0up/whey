<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\Permission
 * @extends Builder<TModelClass>
 */
class PermissionBuilder extends Builder
{
    /** Builder methods - start */
    public function whereSlugIn(array $slugs): self
    {
        return $this->whereIn('slug', $slugs);
    }
    /** Builder methods - end */
}
