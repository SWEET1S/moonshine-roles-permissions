<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use Spatie\Permission\Traits\HasRoles;

trait MoonshineRBACHasRoles
{
    use HasRoles;

    public function guardName(): string
    {
        return config('moonshine.auth.guard');
    }
}
