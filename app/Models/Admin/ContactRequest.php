<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable
        = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'subject',
            'message',
        ];
}
