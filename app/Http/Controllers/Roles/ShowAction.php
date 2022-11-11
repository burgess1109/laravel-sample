<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\ShowRequest;
use App\Repositories\Role;

class ShowAction extends Controller
{
    private Role $roleRepository;

    public function __construct(Role $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(int $id, ShowRequest $request)
    {
        $role = $this->roleRepository->getById($id);
        if (empty($role)) {
            return response()->json((object)[]);
        }

        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'note' => $role->note,
            'permissionIds' => $role->permissions->pluck('id')->all()
        ]);
    }
}
