<?php

namespace App\Models\Shop;

use Illuminate\Notifications\Notifiable;

class JeduCsutomer extends \Webkul\Customer\Models\Customer
{
    use Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'password',
        'api_token',
        'customer_group_id',
        'subscribed_to_news_letter',
        'is_verified',
        'token',
        'pin',
        'pin_expire',
        'notes',
        'status',
    ];

    //TODO use this function for asking extra info
    public function extra_info()
    {
        return true;
    }
}