<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarouselCategory extends Model
{

    protected $fillable
        = [
            'title',
            'order'
        ];

    public function carousel_items(): HasMany
    {
        return $this->hasMany(CarouselItem::class,'carousel_id');
    }
}
