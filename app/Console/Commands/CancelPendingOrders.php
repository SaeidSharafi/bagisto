<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Sales\Repositories\OrderRepository;

class CancelPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cancel-pending';

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
        $orders = $this->orderRepository->findWhere([
            'status' => 'pending',
            ['created_at', '<', now()->subMinutes(15)]
        ]);

        foreach ($orders as $order) {
            $this->orderRepository->cancel($order);
        }
        // $this->info("{$success} users synchronized with moodle, {$failes} failed.");
        // if ($success) {
        //     $msg = __('admin.notifications.sync.users', ['fail' => $failes, 'success' => $success]);
        //     $this->notificationRepository->create([
        //         'type'     => 'sync',
        //         'message'  => $msg,
        //         'order_id' => null
        //     ]);
        // }

    }
}
