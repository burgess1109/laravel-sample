<?php

namespace App\Services;

use App\Repositories\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Permission
{
    private Request $request;
    private Role $roleRepository;

    public function __construct(Request $request, Role $roleRepository)
    {
        $this->request = $request;
        $this->roleRepository = $roleRepository;
    }

    public function can(string $roleCode, string $action): bool
    {
        $user = json_decode(Redis::get($this->request->attributes->get('jti')));
        $role = $this->roleRepository->getById($user->role_id);
        if (empty($role)) {
            return false;
        }
        return $role->permissions->where('code', $roleCode)->where('action', $action)->count() > 0;
    }
}
