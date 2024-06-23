<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{

    protected $fillable =[
        'title',
        'address',
        'phone',
        'link',
        'order'
    ];
}
