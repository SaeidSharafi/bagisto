<?php

namespace App\Console\Commands;

use App\Services\HttpRequestService;
use App\Services\SpotPlayerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;

class GenerateSpotLicenceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spot:generate {order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @param  string  $signature
     */
    public function __construct(
        protected OrderRepository $orderRepository
    ) {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $order = $this->orderRepository->findOneWhere([
            'status' => 'completed',
            'id' => $this->argument('order')
        ]);
        try {
            if ($order->status === Order::STATUS_COMPLETED) {
                Log::info("UpdateSpotLicense",$order->toArray());
                foreach ($order->items as $item) {
                    if ($item->product?->spot_id) {
                        Log::info("UpdateSpotLicense: {$item->product->spot_id}");
                        SpotPlayerService::generateLicense($order, $item);
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }


    }
}
