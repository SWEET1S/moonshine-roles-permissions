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
- resourceName: The name of the resource for which the permissions file will be generated.

---
### 3) Command: `php artisan moonshine-roles-perm:policy {model} {--name=}`

This command generates a policy file for the specified model.

Required parameters:
- model: The name of the model for which the policy file will be generated.

Optional parameters:
- name: The name of the policy file. If not specified, the name will be the name of the model with the suffix "Policy".

---
### 4) Command: `php artisan moonshine-roles-perm:publish`

This command publishes the UserResource / RoleResource and UserPolicy / RolePolicy. Command create permissions UserResource.* / RoleResource.*.

---
### 5) Command: `php artisan moonshine-roles-perm:role {name}`

This command creates a new role with all existing permissions.

Required parameters:
- name: The name of the role.
---
### 6) Command: `php artisan moonshine-roles-perm:user`

This command creates a new user with possibility to assign role.

