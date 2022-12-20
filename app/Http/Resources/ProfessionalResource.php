<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'license' => $this->license,
            'phone' => $this->user->phone,
            'role' => $this->user->role,
            'user_id' => $this->user_id,
            'company_id' => $this->company_id,
            'validated_at' => $this->validated_at
        ];
    }
}
