<?php

namespace Sweet1s\MoonshineRBAC;

use Illuminate\Support\Collection;

class Abilities
{
    public static function getAbilities(): Collection
    {
        return collect([
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
            'massDelete',
            'restore',
            'forceDelete',
        ]);
    }
}
