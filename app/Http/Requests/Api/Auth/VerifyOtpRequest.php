<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|exists:users,phone',
            'otp'   => 'required|string|size:4',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.exists'   => 'Phone number not found.',
            'otp.required'   => 'OTP is required.',
            'otp.size'       => 'OTP must be 4 digits.',
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
