<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\DestroyRequest;
use App\Repositories\Role;
use App\Repositories\User;
use Exception;

class DestroyAction extends Controller
{
    private Role $roleRepository;
    private User $userRepository;

    public function __construct(Role $roleRepository, User $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $id, DestroyRequest $request)
    {
        if ($this->userRepository->countByRoleId($id) > 0) {
            throw new Exception('The role id is in use.');
        }
        return response()->json(['result' => (bool)$this->roleRepository->delete($id)]);
    }
}
