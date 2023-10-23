<?php

declare(strict_types=1);

namespace App\View\Components\auth;

use App\Enums\Role;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DemoUserRoleSelector extends Component
{
    public function __construct(
        public Role $role,
        public string $color,
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('components.auth.demo-user-role-selector');
    }
}
