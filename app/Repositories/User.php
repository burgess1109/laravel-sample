<?php

namespace App\Repositories;

use App\Models\User as UserModel;

class User
{
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function getByAccount(string $account)
    {
        return $this->userModel->where('account', $account)->first();
    }

    public function getList()
    {
        return $this->userModel->all();
    }

    public function getById(int $id)
    {
        return $this->userModel->with(['role'])->find($id);
    }

    public function create(array $data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->userModel->create($data);
    }

    public function delete(int $id)
    {
        return $this->userModel->where('id', $id)->delete();
    }

    public function countByRoleId(int $roleId): int
    {
        return $this->userModel->where('role_id', $roleId)->count();
    }

    public function update(int $id, array $data)
    {
        $user = $this->getById($id);
        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }
        if (!empty($data['roleId'])) {
            $user->role_id = $data['roleId'];
        }

        $user->save();
        return $user;
    }
}
