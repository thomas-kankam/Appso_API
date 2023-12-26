<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'role' => 'required|string',
            'email' => 'required|unique:doctors,email',
            'phone_number' => 'required|unique:doctors,phone_number',
            'hospital_name' => 'required|string',
            'national_id' => 'required|string|unique:doctors,national_id',
            'national_id_front_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'national_id_back_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'passport_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            "password" => ["required"],
        ];
    }

    public function messages()
    {
        return [
            "first_name" => "Your first name is required",
            "last_name" => "Your last name is required",
            "role" => "Your role is required",
            "email" => "Your email is required",
            "email.unique" => "This email is already registered",
            "phone_number.required" => "Your phone number is required",
            "phone_number.unique" => "This phone number is already registered",
            "hospital_name" => "Your hospital name is required",
            "national_id" => "Your National ID is required",
            "national_id.unique" => "This National ID is already registered",
            "national_id_front_image.required" => "Your National ID front is required",
            "national_id_back_image.required" => "Your National ID back is required",
            "passport_picture.required" => "Your passport picture is required",
            "password.required" => "Your password is required",
        ];
    }
}
