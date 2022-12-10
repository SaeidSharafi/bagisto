<?php

namespace App\Console\Commands;

use App\Models\Shop\JeduCustomer;
use Illuminate\Console\Command;
use Webkul\Sales\Models\Order;

class FixCustomerPhone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:fix-phone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::query()
            ->whereNull('customer_phone')
            ->limit(50)
            ->get();
        $success = 0;
        $failes = 0;
        foreach ($orders as $order) {
            $customer = JeduCustomer::find($order->customer_id);
            if ($customer) {
                $order->customer_phone = $customer->phone;
                $order->save();
                $success++;
                continue;
            }
            $failes++;
        }
        $this->info("{$success} orders fixed, {$failes} failed.");
    }
}
