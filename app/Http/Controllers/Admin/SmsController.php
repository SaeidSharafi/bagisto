<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Kuro\LaravelSms\Model\SmsLog;

class SmsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');

    }

    public function index()
    {
        $logs = SmsLog::query()
            ->paginate(25);

        return view('admin.sms.index',compact('logs'));
    }
}
