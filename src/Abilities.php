<?php

namespace Sweet1s\MoonshineRBAC;

class Abilities
{
    public static function getAbilities(): array
    {
        return [
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
            'massDelete',
            'restore',
            'forceDelete',
        ];
    }
}
