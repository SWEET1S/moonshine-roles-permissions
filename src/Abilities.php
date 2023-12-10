<?php

namespace Sweet1s\MoonshineRolesPermissions;

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
