<?php

namespace Kuro\LaravelSms\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class SmsLog extends Model
{
    use HasFactory, Prunable;

    protected $fillable
        = [
            'response',
            'from',
            'to',
            'pattern',
            'content'
        ];

    public function prunable()
    {
        return static::whereDate('created_at', '<=', now()->subDays(30));
    }
}
