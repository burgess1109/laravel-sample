<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Repositories\User;

class StoreAction extends Controller
{
    private User $userRepository;

    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(StoreRequest $request)
    {
        $user = $this->userRepository->create([
            'account' => $request->request->get('account'),
            'password' => $request->request->get('password'),
            'email' => $request->request->get('email', ''),
            'role_id' => $request->request->get('roleId'),
        ]);

        return response()->json([
            'id' => $user->id,
            'account' => $user->account,
            'email' => $request->request->get('email', ''),
            'roleId' => $request->request->get('roleId'),
        ]);
    }
}
