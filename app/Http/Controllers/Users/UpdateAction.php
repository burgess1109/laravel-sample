<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateRequest;
use App\Repositories\User;

class UpdateAction extends Controller
{
    private User $userRepository;

    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $id, UpdateRequest $request)
    {
        $user = $this->userRepository->update(
            $id,
            [
                'password' => $request->request->get('password'),
                'email' => $request->request->get('email'),
                'roleId' => $request->request->get('roleId'),
            ]
        );

        return response()->json([
            'id' => $user->id,
            'account' => $user->account,
            'email' => $user->email,
            'roleId' => $user->role_id
        ]);
    }
}
