<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/|unique:users,phone',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.unique'   => 'This phone number is already registered.',
            'phone.regex'    => 'Please enter a valid phone number.',
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        $errors  = $validator->errors();
        $message = $errors->first();

        $response = response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], 422);

        throw new HttpResponseException($response);
    }
}
