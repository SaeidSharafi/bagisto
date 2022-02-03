<?php

namespace App\Jobs;

use App\Services\SmsBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sms;
    protected $op;
    public $failOnTimeout = true;
    public $tries = 2;
    public $timeout = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SmsBuilder $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->sms){
            \Log::info("sending SMS");
            try {
                \Log::info(json_encode($this->sms->build(),
                    JSON_THROW_ON_ERROR));
            }catch (\Exception $e){
                $this->fail($e);
            }
        }
    }
    public function failed(Throwable $exception)
    {
        report($exception);
    }
}
