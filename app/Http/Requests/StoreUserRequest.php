<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'data.*.name' => 'string|max:100',
            'data.*.email' => 'string|max:100|unique:App\Models\User,email',
            'data.*.password' => 'string',
            'data.*.unit_id' => 'numeric|exists:App\Models\Unit,id',
            'data.*.join_date' => 'date_format:Y-m-d',
            'data.*.jabatan' => 'array',
            'data.*.jabatan.*.id' => 'numeric|exists:App\Models\Jabatan,id'
        ];
    }
}
