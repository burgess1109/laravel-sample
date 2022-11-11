<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\IndexRequest;
use App\Repositories\Role;

class IndexAction extends Controller
{
    private Role $roleRepository;

    public function __construct(Role $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(IndexRequest $request)
    {
        $roles = $this->roleRepository->getList();
        return response()->json($this->formatResponse($roles));
    }

    private function formatResponse($rows)
    {
        $response = [];
        foreach ($rows as $row)
        {
            $response[] = [
                'id' => $row->id,
                'name' => $row->name,
                'note' => $row->note,
            ];
        }

        return $response;
    }
}
