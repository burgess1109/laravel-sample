<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\DestroyRequest;
use App\Repositories\User;

class DestroyAction extends Controller
{
    private User $userRepository;

    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $id, DestroyRequest $request)
    {
        return response()->json(['result' => (bool)$this->userRepository->delete($id)]);
    }
}
