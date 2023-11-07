<?php

namespace App\Console\Commands;

use App\Models\Shop\JeduCustomer;
use Illuminate\Console\Command;

class DeleteNonActiveCustomerCommand extends Command
{
    protected $signature = 'customer:delete-non-active';

    protected $description = 'Remove customers that does not have completed their information';

    public function handle(): void
    {
        JeduCustomer::query()
            ->whereNull('email')
            ->whereNull('first_name')
            ->whereNull('last_name')
            ->whereNull('national_code')
            ->delete();
    }
}
