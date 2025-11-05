<?php

namespace App\Http\Requests\Web\V1\Setting\Mail;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mail_mailer'     => 'required|string',
            'mail_host'       => 'required|string',
            'mail_port'       => 'required|string',
            'mail_username'   => 'nullable|email',
            'mail_password'   => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_address'    => 'required|email',
            'condition'    => 'required',
        ];
    }
}
