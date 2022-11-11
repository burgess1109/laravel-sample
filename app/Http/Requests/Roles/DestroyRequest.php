<?php

namespace App\Http\Requests\Roles;

use App\Services\Permission;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Permission $permission
     * @return bool
     */
    public function authorize(Permission $permission)
    {
        return $permission->can('role', 'delete');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:roles,id']
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['id'] = (int)$this->route('id');
        return $data;
    }
}
