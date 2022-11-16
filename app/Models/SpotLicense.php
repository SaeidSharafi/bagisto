<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Models\OrderProxy;

class SpotLicense extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'order_id',
            'product_id',
            '_id',
            'key',
            'url',
        ];

    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }
}
