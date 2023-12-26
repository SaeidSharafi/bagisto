<?php

namespace App\Console\Commands;

use App\Services\HttpRequestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Webkul\Sales\Repositories\OrderRepository;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api';

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
            'id' => 1154
        ]);
        try {

            $request = new HttpRequestService($order, HttpRequestService::OP_UPDATE_REGISTERATION);
            \Log::info("sending API Request");

            $response = $request->build();

            \Log::info($response);
        } catch (\Exception $e) {
            echo $e->getMessage();
            Log::error($e->getMessage());
        }


    }
}
