<?php

namespace App\Repositories;

use App\Models\Role as RoleModel;

class Role
{
    private RoleModel $roleModel;

    public function __construct(RoleModel $roleModel)
    {
        $this->roleModel = $roleModel;
    }

    public function getList()
    {
        return $this->roleModel->all();
    }

    public function getById(int $id)
    {
        return $this->roleModel->with(['permissions'])->find($id);
    }

    public function create(array $data)
    {
        $permissionIds = $data['permissionIds'];
        unset($data['permissionIds']);
        $role = $this->roleModel->create($data);
        foreach ($permissionIds as $permissionId) {
            $role->permissions()->attach($permissionId);
        }

        return $role;
    }

    public function delete(int $id)
    {
        return $this->roleModel->where('id', $id)->delete();
    }

    public function update(int $id, array $data)
    {
        $role = $this->getById($id);
        if (!empty($data['name'])) {
            $role->name = $data['name'];
        }
        if (isset($data['note'])) {
            $role->note = $data['note'];
        }
        if (isset($data['permissionIds'])) {
            $role->permissions()->detach();
            foreach ($data['permissionIds'] as $permissionId) {
                $role->permissions()->attach($permissionId);
            }
        }

        $role->save();
        $role->load('permissions');
        return $role;
    }
}
