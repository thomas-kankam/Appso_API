<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'role',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'phone_number',
        'bio_info',
        'hospital_name',
        'national_id',
        'country',
        'national_id_front_image',
        'national_id_back_image',
        'passport_picture',
        'specialty',
        'working_hours',
        'appointment_type',
        'appointment_duration',
        'consultation_fee',
        'data_range',
        'no_show_fee',
        'account_status',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $casts = [
        'data_range' => 'array',
        "working_hours" => "array",
        'email_verified_at' => 'datetime',
        "phone_verified_at" => "datetime",
    ];
}
