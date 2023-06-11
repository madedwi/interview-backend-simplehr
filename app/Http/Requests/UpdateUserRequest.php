<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'data' => 'array|required',
            'data.0.name' => 'string|max:100',
            'data.0.email' => [
                'string',
                'max:100',
                Rule::unique('users', 'email')->ignore($this->route()->parameter('pegawai'), 'id')
            ],
            'data.0.password' => 'string',
            'data.0.unit_id' => 'numeric|exists:App\Models\Unit,id',
            'data.0.join_date' => 'date_format:Y-m-d',
            'data.0.jabatan' => 'array',
            'data.0.jabatan.*.id' => 'numeric|exists:App\Models\Jabatan,id'
        ];
    }
}
