<?php

namespace App\Http\Controllers;

use App\Models\SpotLicense;
use App\Services\ImsApiService;
use App\Services\MoodleFormatService;
use App\Services\MoodleService;
use App\Services\ProductService;
use App\Services\SpotPlayerService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Sales\Repositories\OrderRepository;

class ImsRedirectController extends Controller
{

    /**
     * Current customer.
     *
     * @var \Webkul\Customer\Contracts\Customer
     */
    protected $currentCustomer;

    /**
     * OrderrRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderrRepository object
     *
     * @var \Webkul\Product\Repositories\ProductFlatRepository
     */
    protected $productFlatRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('customer');
        $this->currentCustomer = auth()->guard('customer')->user();
    }

    public function __invoke()
    {
        if (!auth()->guard('customer')->check()) {
            session()->flash("Unathrozied user");
            redirect()->back();
        }

       $url = ImsApiService::getMagicLink($this->currentCustomer->phone);
        $url = str_replace('ims-localhost','ims.localhost',$url);
        return redirect()->to($url);

    }
}
