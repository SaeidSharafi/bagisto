<?php

namespace App\Console\Commands;

use App\Services\HttpRequestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Webkul\Sales\Repositories\OrderRepository;

class CompleteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change all orders status from processing to completed';

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
        $orders = $this->orderRepository->findWhere([
            'status' => 'processing'
        ]);
        try {
            foreach ($orders as $order) {
                $this->orderRepository->updateOrderStatus($order, 'completed');
                echo $order->increment_id . " has set to Completed \n";
            }

        } catch (\Exception $e) {
            throw $e;
            Log::error($e->getMessage());
        }
    }
}
