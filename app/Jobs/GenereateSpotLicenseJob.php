<?php

namespace App\Jobs;

use App\Models\SpotLicense;
use App\Services\SpotPlayerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Sales\Contracts\Order;
use Webkul\Sales\Contracts\OrderItem;

class GenereateSpotLicenseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Order $order, private OrderItem $orderItem)
    {
    }

    public function handle(): void
    {
        SpotPlayerService::generateLicense($this->order, $this->orderItem);
    }
}
