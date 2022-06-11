<?php

namespace App\Console\Commands;

use App\Models\Shop\JeduCustomer;
use App\Services\MoodleService;
use Illuminate\Console\Command;
use Webkul\Customer\Repositories\CustomerRepository;

class SynchMoodleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moodle:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     *  create new SynchMoodleUser instance
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     */
    protected $customerRepository;

    /**
     * @param  string  $signature
     */
    public function __construct(CustomerRepository $customerRepository)
    {
       parent::__construct();

       $this->customerRepository = $customerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $customers = JeduCustomer::query()
            ->where( 'is_moodle_user' , 1)
            ->where( 'moodle_synch',0)
            ->get();
        $success = 0;
        $failes = 0;
        foreach ($customers as $customer){
           $result = MoodleService::createUser($customer);
           if ($result){
               $customer->moodle_synch = 1;
               $customer->save();
               $success++;
               continue;
           }
           $failes++;
        }
        $this->info("{$success} users synchronized with moodle, {$failes} failed.");

    }
}
