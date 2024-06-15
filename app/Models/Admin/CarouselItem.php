<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarouselItem extends Model
{
    protected $fillable =[
        'title',
        'image',
        'url',
        'order',
        'carousel_id',
    ];

    public function carousel(): BelongsTo
    {
        return $this->belongsTo(CarouselCategory::class,'carousel_id');
    }
}
