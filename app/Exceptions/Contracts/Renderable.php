<?php

declare(strict_types=1);

namespace App\Exceptions\Contracts;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface Renderable
{
    public function render(Request $request): Response|RedirectResponse|false;
}
