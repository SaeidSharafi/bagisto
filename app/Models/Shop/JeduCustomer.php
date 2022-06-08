<?php

namespace App\Models\Shop;

use App\Models\MoodleEnrolment;
use App\Traits\hasOTP;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;

class JeduCustomer extends \Webkul\Customer\Models\Customer
{
    use Notifiable,hasOTP;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'national_code',
        'email',
        'phone',
        'password',
        'api_token',
        'customer_group_id',
        'subscribed_to_news_letter',
        'is_verified',
        'token',
        'notes',
        'father_name',
        'education_field',
        'status',
        'is_moodle_user',
        'moodle_synch',
    ];
    protected $casts = [
        'date_of_birth' => 'date:Y/m/d',
    ];

    public function username()
    {
        return 'phone';
    }
    public function getPhoneForPasswordReset()
    {
        return $this->phone;
    }

    public function incomplete() : Attribute
    {
        return Attribute::make(
            get: fn () => (!$this->first_name
                || !$this->last_name
                || !$this->national_code
                || !$this->gender),
        );
    }

    public function moodle_enrolments(){
        return $this->hasMany(MoodleEnrolment::class);
    }
    //TODO use this function for asking extra info
    public function extra_info()
    {
        return true;
    }
}