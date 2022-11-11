<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\User;
use App\Services\SampleJWT;
use Illuminate\Auth\AuthenticationException;

class LoginAction extends Controller
{
    private User $userRepository;
    private SampleJWT $sampleJWT;

    public function __construct(User $userRepository, SampleJWT $sampleJWT)
    {
        $this->userRepository = $userRepository;
        $this->sampleJWT = $sampleJWT;
    }

    public function __invoke(LoginRequest $request)
    {
        $user = $this->userRepository->getByAccount($request->request->get('account'));
        if (empty($user)) {
            throw new AuthenticationException('user not found');
        }

        if (!password_verify($request->request->get('password'), $user->password)) {
            throw new AuthenticationException('login failed');
        }

        return response()->json(['token' => $this->sampleJWT->encode($user)]);
    }
}
