## MoonShine Roles-Permissions

### Description

This package is an extension exclusively designed for the [MoonShine Admin Panel](https://github.com/moonshine-software/moonshine), building upon the functionality of the [Spatie Laravel Permissions](https://github.com/spatie/laravel-permission) package. The primary purpose of this extension is to streamline role-based access control (RBAC) within the MoonShine Admin Panel. By utilizing this package, you can efficiently assign permissions to roles and then grant those roles to users, simplifying the process of managing permissions on a role-based level rather than individually assigning them to each user.

---

## Features

- [x] Role-Based Access Control (RBAC): Enhance your MoonShine Admin Panel with a comprehensive role-based permission system, allowing you to group users with similar permissions into roles and manage access more efficiently.
- [x] Role Assignment: Seamlessly associate permissions with roles, making it effortless to define the access rights for specific groups of users.
- [X] Bulk Role Assignment: Grant multiple users the same role simultaneously, reducing the manual effort required to manage permissions across large user bases.
- [x] Seamless Integration: The package seamlessly integrates with the MoonShine Admin Panel and extends the capabilities of the Spatie Laravel Permissions package specifically for this panel.

---

## Important

Before using the package, it is crucial to understand that you need to use a different user model instead of "MoonShineUser." The package requires the utilization of the Spatie Laravel Permission package and an empty "moonshine_user_permissions" table. Please note that when the "moonshine_user_permissions" table contains other permissions for users, MoonShine Admin Panel utilizes its internal Policy implementation, disregarding any existing Policy defined in "App/Policy."

---

## Installation

1. Install the [Spatie Laravel Permissions](https://github.com/spatie/laravel-permission) package and follow the instructions in the documentation to set up the package correctly.

2. Install the package via composer:

```bash
composer require sweet1s/moonshine-roles-permissions
```
3. In the MoonShine config file, change the user model to the default User model or the model you want to use for the admin panel.

```PHP
...
'providers' => [
    'moonshine' => [
        ...
        'model'  => \App\Models\User::class,
    ],
],
...
```
4. For the user model, add the following:

```PHP
<?php

namespace App\Models;

...
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    ...
    use HasRoles;

    protected $fillable = [
        'email',
        'role_id',
        'password',
        'name',
        'avatar'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    ...
}
```

5. In the AuthServiceProvider.php file, add the following:

```PHP
...
use Illuminate\Support\Facades\Gate;

...

public function boot()
{
    ...

    Gate::before(function ($user, $ability) {
        return $user?->role?->id === 1 ? true : null;
    });
}
```

6. Run the following command to publish the package's config file:

```bash
php artisan moonshine-roles-perm:install
```
7. Create a user with new modal and assign the role "Super Admin" to it.

```bash
php artisan moonshine-roles-perm:user
```

8. Add new MoonShine resource to your MoonShineServiceProvider file, like this:

```PHP
MenuGroup::make('System', [
        MenuItem::make('Admins', new App\MoonShine\Resources\UserResource(), 'heroicons.outline.users'),
        MenuItem::make('Roles', new App\MoonShine\Resources\RoleResource(), 'heroicons.outline.shield-exclamation'),
    ], 'heroicons.outline.user-group'),
...
```
---
## Usage

1. [Creating a section in the admin panel with MoonShine](https://moonshine.cutcode.dev/section/resources-index?change-moonshine-locale=en)
```bash
php artisan moonshine:resource Post
```
2. Generate a new policy for the model
```bash
php artisan moonshine-roles-perm:policy Post --namePolicy="PostPolicy"
```
3. For Resource, add the following:

```PHP
public static bool $withPolicy = true;
```
4. Create a role in the MoonShine Admin Panel and assign permissions to it.

---

#### _How does it look in the Admin Panel ?_
![How does it look in the Admin Panel](./.docs/images/how-look-role.jpg)
