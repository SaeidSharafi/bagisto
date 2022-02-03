<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\Slider;

class JeduSlider extends Slider
{
    protected $fillable = [
        'title',
        'path',
        'show_content',
        'description',
        'button',
        'channel_id',
        'locale',
        'expired_at',
        'sort_order'
    ];
}
