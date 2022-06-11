<?php

namespace App\Console\Commands;

use App\Models\MoodleEnrolment;
use App\Models\Shop\JeduCustomer;
use App\Services\MoodleService;
use Illuminate\Console\Command;

class SynchMoodleEnrolments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moodle:enrol';

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
        $customers = JeduCustomer::query()
            ->has('moodle_enrolments')
            ->get();
        $success = 0;
        $failes = 0;
        foreach ($customers as $customer) {
            $result = MoodleService::enrolUserInCourse($customer);
            if ($result) {
              MoodleEnrolment::query()
                  ->where('customer_national_code',$customer->national_code)
                  ->delete();
                $success++;
                continue;
            }
            $failes++;
        }
        $this->info("{$success} users enrolments synchronized with moodle, {$failes} failed.");
    }
}
