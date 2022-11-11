<?php

namespace App\Http\Requests\Users;

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
        return $permission->can('user', 'create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'account' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'email' => ['string', 'max:255', 'email'],
            'roleId' => ['required', 'integer', 'exists:roles,id'],
        ];
    }
}
