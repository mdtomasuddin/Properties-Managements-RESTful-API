<?php

namespace App\Http\Resources\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $user = [
            'id' => $data['user']['id'],
            'first_name' => $data['user']['first_name'],
            'last_name' => $data['user']['last_name'],
            'handle' => $data['user']['handle'],
            'email' => $data['user']['email'],
            'role' => $data['user']['role']['name'],
        ];
        return [
            'token' => $data['token'],
            'verify' => $data['verify'],
            'user' => $user,
        ];
    }
}
