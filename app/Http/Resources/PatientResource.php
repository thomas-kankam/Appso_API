<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'middle_name' => $this->when($this->middle_name, $this->middle_name),
            'email' => $this->email,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'age' => $this->age,
            'phone_number' => $this->phone_number,
            'bio_info' => $this->bio_info,
            'national_id' => $this->national_id,
            'country' => $this->country,
            'national_id_front_image' => $this->national_id_front_image,
            'national_id_back_image' => $this->national_id_back_image,
            'passport_picture' => $this->passport_picture,
            'occupation' => $this->occupation,
            'account_status' => $this->account_status,
            'is_verified' => $this->verified,
        ];
    }
}
