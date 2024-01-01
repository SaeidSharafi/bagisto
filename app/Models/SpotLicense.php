<?php

namespace App\Models;

use App\Listeners\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
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

    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
