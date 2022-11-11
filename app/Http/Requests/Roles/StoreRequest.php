<?php

namespace App\Http\Requests\Roles;

use App\Services\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Permission $permission
     * @return bool
     */
    public function authorize(Permission $permission)
    {
        return $permission->can('role', 'create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'note' => ['string', 'max:255'],
            'permissionIds' => ['array'],
            'permissionIds.*' => ['exists:permissions,id'],
        ];
    }
}
