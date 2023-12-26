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
            // $process = new Process(['ping', 'ims.localhost']);
            //     $process = Process::fromShellCommandline("curl http://ims.localhost");

            // $processOutput = '';

            // $captureOutput = function ($type, $line) use (&$processOutput) {
            //     $processOutput .= $line;
            // };

            // $process->setTimeout(null)
            //     ->run($captureOutput);
            //     echo $processOutput;
            // if ($process->getExitCode()) {


            //     throw new \Exception('exit');
            // }

            $request = new HttpRequestService($order, HttpRequestService::OP_UPDATE_REGISTERATION);
            \Log::info("sending API Request");

            $response = $request->build();
            \Log::info($response);
        } catch (\Exception $e) {
            echo $e->getMessage();
            Log::error($e->getMessage());
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
