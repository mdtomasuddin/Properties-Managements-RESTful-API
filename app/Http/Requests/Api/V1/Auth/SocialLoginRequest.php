<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Traits\V1\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SocialLoginRequest extends FormRequest
{
    use ApiResponse;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'provider' => 'required|in:google,facebook',
        ];
    }


    /**
     * Get custom validation error messages.
     *
     * This method defines custom error messages for validation rules applied
     * to the incoming request data. The messages correspond to specific
     * fields like 'token' and 'provider', providing clear and descriptive
     * feedback when validation fails.
     *
     * @return array The custom error messages for validation failures.
     */
    public function messages(): array
    {
        return [
            'token.required' => 'Token is required',
            'provider.required' => 'Provider is required',
            'provider.in' => 'Invalid provider selected. The available options are Google & Facebook.',
        ];
    }



    /**
     * failedValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     * @return never
     */
    protected function failedValidation(Validator $validator): never
    {
        $fieldsToCheck = ['token', 'provider'];
        $message = 'Validation error'; // Default message

        foreach ($fieldsToCheck as $field) {
            $errors = $validator->errors()->get($field);
            if (!empty($errors)) {
                $message = $errors[0];
                break;
            }
        }

        $response = $this->error(
            422,
            $message,
            $validator->errors(),
        );

        throw new ValidationException($validator, $response);
    }
}
