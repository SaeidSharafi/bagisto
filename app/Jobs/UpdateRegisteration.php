<?php

namespace App\Jobs;

use App\Services\HttpRequestService;
use App\Services\RouyeshAPIService;
use App\Services\SmsBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateRegisteration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    public $failOnTimeout = true;
    public $tries = 2;
    public $timeout = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($this->order) {
            $request = new HttpRequestService($this->order, HttpRequestService::OP_UPDATE_REGISTERATION);
            \Log::info("sending API Request for IMS: ". $this->order->id );
            try {
                $request->build();
            } catch (\Exception $e) {
                $this->fail($e);
            }
        }
    }

    public function failed($exception)
    {
        report($exception);
    }
}
