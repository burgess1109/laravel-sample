<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\UpdateRequest;
use App\Repositories\Role;

class UpdateAction extends Controller
{
    private Role $roleRepository;

    public function __construct(Role $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(int $id, UpdateRequest $request)
    {
        $role = $this->roleRepository->update(
            $id,
            [
                'name' => $request->request->get('name'),
                'note' => $request->request->get('note'),
                'permissionIds' => $request->request->all('permissionIds'),
            ]
        );

        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'note' => $role->note,
            'permissionIds' => $role->permissions->pluck('id')->all()
        ]);
    }
}
