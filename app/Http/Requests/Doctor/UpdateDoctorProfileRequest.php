<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorProfileRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'email' => 'required|unique:doctors,email',
            'phone_number' => 'required|unique:doctors,phone_number',
            'hospital_name' => 'required|string',
            'national_id' => 'required|string|unique:doctors,national_id',
            'specialty' => 'required|string',
            'working_hours' => 'required|string',
            'appointment_type' => 'required|string',
            'consultation_fee' => 'required|string',
            'no_show_fee' => 'required|string',
            'appointment_duration' => 'required|string',
            'data_range' => 'required|string',
            // 'national_id_front_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'national_id_back_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'passport_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            "first_name" => "Your first name is required",
            "first_name.required" => "Your first name is required",
            "first_name.string" => "Your first name must be a string",
            "last_name" => "Your last name is required",
            "last_name.required" => "Your last name is required",
            "last_name.string" => "Your last name must be a string",
            "middle_name.string" => "Your middle name must be a string",
            "email" => "Your email is required",
            "email.required" => "Your email is required",
            "email.unique" => "This email is already registered",
            "phone_number.required" => "Your phone number is required",
            "phone_number.unique" => "This phone number is already registered",
            "hospital_name.required" => "Your hospital name is required",
            "national_id" => "Your National ID is required",
            "national_id.required" => "Your National ID is required",
            "national_id.unique" => "This National ID is already registered",
            "specialty.required" => "Your specialty is required",
            "working_hours.required" => "Your working hours is required",
            "appointment_type.required" => "Your appointment type is required",
            "consultation_fee.required" => "Your consultation fee is required",
            "no_show_fee.required" => "Your no show fee is required",
            "appointment_duration.required" => "Your appointment duration is required",
            "data_range.required" => "Your data range is required",
            // "national_id_front_image.required" => "Your National ID front is required",
            // "national_id_back_image.required" => "Your National ID back is required",
            // "passport_picture.required" => "Your passport picture is required",
        ];
    }
}
