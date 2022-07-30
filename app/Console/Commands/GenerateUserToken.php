<?php

namespace App\Console\Commands;

use App\Models\Shop\JeduCustomer;
use App\Services\MoodleService;
use Illuminate\Console\Command;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Notification\Repositories\NotificationRepository;

class GenerateUserToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:generate-token';

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
        protected CustomerRepository $customerRepository)
    {
       parent::__construct();


    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $customers = JeduCustomer::query()
            ->whereNull('token')
            ->get();
        $success = 0;

        foreach ($customers as $customer){
            $customer->token = md5(uniqid(rand(), true))
                .md5($customer->phone);
            $customer->save();
            $success++;
        }
        $this->info("{$success} token generated");

    }
}
