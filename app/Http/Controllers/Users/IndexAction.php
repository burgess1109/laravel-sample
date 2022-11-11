<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\IndexRequest;
use App\Repositories\User;

class IndexAction extends Controller
{
    private User $userRepository;

    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(IndexRequest $request)
    {
        $users = $this->userRepository->getList();
        return response()->json($this->formatResponse($users));
    }

    private function formatResponse($rows)
    {
        $response = [];
        foreach ($rows as $row)
        {
            $response[] = [
                'id' => $row->id,
                'account' => $row->account,
                'email' => $row->email,
            ];
        }

        return $response;
    }
}
