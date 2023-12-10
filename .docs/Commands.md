## Commands

---

### 1) Command: `php artisan moonshine-roles-perm:install`

This command starts the installation process for the package.
It migrates the database, publishes the resources/policies, and creates
the default roles and permissions.

---

### 2) Command: `php artisan moonshine-roles-perm:permissions {resourceName}`

This command generates a permissions file for the specified resource.

Required parameters:

-   resourceName: The name of the resource for which the permissions file will be generated.

---

### 3) Command: `php artisan moonshine-roles-perm:role {name}`

This command creates a new role with all existing permissions.

Required parameters:

-   name: The name of the role.

---

### 4) Command: `php artisan moonshine-roles-perm:user`

This command creates a new user with possibility to assign role.

---

### 5) Command: `php artisan moonshine-roles-perm:assign {permission} {guard?}`

This command assigns a permission to a role. If the permission does not exist, you will be prompted to create it.

Required parameters:

-   permission: The name of the permission.

Optional parameters:

-   guard: The name of the guard. If not specified, the default guard will be used.

---

### 6) Command: `php artisan moonshine-roles-perm:resource {name?} {--m|model=} {--t|title=} {--s|singleton} {--id=}`

This command creates a new resource with policy and permissions.

Optional parameters:

-   name: The name of the resource. If not specified, you will be prompted to enter it.
-   model: The name of the model. If not specified, the name of the resource will be used.
-   title: The title of the resource. If not specified, the name of the resource will be used.
-   singleton: If specified, the resource will be created as a singleton.
-   id: The name of the id column. If not specified, the default id column will be used.
