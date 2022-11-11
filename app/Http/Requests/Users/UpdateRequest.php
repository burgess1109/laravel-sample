<?php

namespace App\Http\Requests\Users;

use App\Services\Permission;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Permission $permission
     * @return bool
     */
    public function authorize(Permission $permission)
    {
        return $permission->can('user', 'update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:users,id'],
            'password' => ['string', 'max:255'],
            'email' => ['string', 'max:255', 'email'],
            'roleId' => ['integer', 'exists:roles,id'],
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['id'] = $this->route('id');
        return $data;
    }
}
