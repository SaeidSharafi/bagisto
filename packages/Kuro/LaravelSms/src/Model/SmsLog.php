<?php

namespace Kuro\LaravelSms\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable=[
        'response',
        'from' ,
        'to' ,
        'pattern' ,
        'content'
    ];
}
