<?php

namespace App\Models\Shop;

use App\Traits\hasOTP;
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

    public function getIncompleteAttribute()
    {
        return (!$this->first_name
        || !$this->last_name
        || !$this->national_code
        || !$this->gender);
    }

    //TODO use this function for asking extra info
    public function extra_info()
    {
        return true;
    }
}