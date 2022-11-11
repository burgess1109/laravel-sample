<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ShowRequest;
use App\Repositories\User;

class ShowAction extends Controller
{
    private User $userRepository;

    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $id, ShowRequest $request)
    {
        $user = $this->userRepository->getById($id);
        if (empty($user)) {
            return response()->json((object)[]);
        }

        return response()->json([
            'id' => $user->id,
            'account' => $user->account,
            'email' => $user->email,
            'roleId' => $user->role_id
        ]);
    }
}
