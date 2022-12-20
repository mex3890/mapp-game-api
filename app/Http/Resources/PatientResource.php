<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => true,
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'parent_name' => $this->user->name,
            'parent_email' => $this->user->email,
            'parent_phone' => $this->user->phone,
            'created_at' => $this->created_at
        ];
    }
}
