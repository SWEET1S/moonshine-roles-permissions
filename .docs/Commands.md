## Commands

---

### 1) Command: `php artisan moonshine-rbac:install`

This command starts the installation process for the package.
It migrates the database, publishes the resources/policies, and creates
the default roles and permissions.

---

### 2) Command: `php artisan moonshine-rbac:permissions {resourceName?}`

This command generates a permissions file for the specified resource.

Optional parameters:

-   resourceName: The name of the resource for which the permissions file will be generated.

---

### 3) Command: `php artisan moonshine-rbac:role {name?} {--all-permissions}`

This command creates a new role with all existing permissions.

Optional parameters:

-   name: The name of the role.
-   --all-permissions: If specified, the role will be assigned all existing permissions.
---

### 4) Command: `php artisan moonshine-rbac:user`

This command creates a new user with possibility to assign role.

---

### 5) Command: `php artisan moonshine-rbac:assign {permission?} {guard?}`

This command assigns a permission to a role. If the permission does not exist, you will be prompted to create it.

Optional parameters:

-   permission: The name of the permission.
-   guard: The name of the guard. If not specified, the default guard will be used.

---

### 6) Command: `php artisan moonshine-rbac:resource {name?} {--m|model=} {--t|title=} {--test} {--pest}`

This command creates a new resource with permissions.

Optional parameters:

-   name: The name of the resource. If not specified, you will be prompted to enter it.
-   model: The name of the model. If not specified, the name of the resource will be used.
-   title: The title of the resource. If not specified, the name of the resource will be used.
-   test or pest: If specified additionally generate a test class.

### 7) Command: `php artisan moonshine-rbac:init-permissions {path?}`

This command creates permissions for resources that have the WithRolePermissions property, but does not apply to existing permissions.

Optional parameters:
-   path: The path to the resource. If not specified, the default path will be used (`app/MoonShine/Resources`).
