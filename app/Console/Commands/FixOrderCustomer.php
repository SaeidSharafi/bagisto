<?php

namespace App\Console\Commands;

use App\Models\Shop\JeduCustomer;
use Illuminate\Console\Command;
use Webkul\Sales\Models\Order;

class FixOrderCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:fix-customer';

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
            ->orWhereNull('customer_email')
            ->orWhereNull('customer_first_name')
            ->orWhereNull('customer_last_name')
            ->limit(50)
            ->get();
        $success = 0;
        $failes = 0;
        foreach ($orders as $order) {
            $customer = JeduCustomer::find($order->customer_id);
            if ($customer) {
                $order->customer_phone = $customer->phone;
                $order->customer_email = $customer->email;
                $order->customer_first_name = $customer->first_name;
                $order->customer_last_name = $customer->last_name;
                $order->save();
                $success++;
                continue;
            }
            $failes++;
        }
        $this->info("{$success} orders fixed, {$failes} failed.");
    }
}
