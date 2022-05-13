<?php

namespace App\Models\Shop;

use App\Http\Resources\JeduCustomer;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{

    protected $fillable =['token','expire','type'];

    public const VERFIY = 'verfiy';
    public const RESET = 'reset';
    public const REGISTER = 'login';

    public function user(){
        return $this->blongsTo(JeduCustomer::class);
    }
    public function remaining(): int
    {
        return $this->expire - time();
    }
    public function isExpired(): bool
    {
        return (($this->expire - time()) <= 0);
    }
}