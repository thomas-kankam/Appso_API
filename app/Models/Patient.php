<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'role',
        'otp',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'phone_number',
        'bio_info',
        'national_id',
        'country',
        'national_id_front_image',
        'national_id_back_image',
        'passport_picture',
        'occupation',
        'account_status',
        'verified'
    ];

    protected $hidden = [
        'password',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        "phone_verified_at" => "datetime",
    ];
}
