<?php

namespace App\Http\Controllers;

use App\Models\SpotLicense;
use App\Services\MoodleFormatService;
use App\Services\MoodleService;
use App\Services\ProductService;
use App\Services\SpotPlayerService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Sales\Repositories\OrderRepository;

class MyCourseController extends Controller
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
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * Repository  $productRepository
     *
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        ProductFlatRepository $productFlatRepository,
    ) {
        $this->middleware('customer');

        $this->currentCustomer = auth()->guard('customer')->user();

        $this->orderRepository = $orderRepository;
        $this->productFlatRepository = $productFlatRepository;

    }

    public function index()
    {
        if (!auth()->guard('customer')->check()) {
            session()->flash("Unathrozied user");
            redirect()->back();
        }

        $orders = $this->orderRepository
            ->getModel()::query()
            ->with('items', function ($query) {
                return $query->select('product_id', 'order_id');
            })
            ->with('spot_license')
            ->where('status', 'completed')
            ->where('customer_id', $this->currentCustomer->id)
            ->get();

        $order_items = $orders->pluck('items')
            ->flatten()
            ->pluck('product_id');


        $spots = $this->productFlatRepository
            ->getModel()::query()
            ->select(
                'product_flat.*',
                'spot_licenses.id as spot_id',
                'spot_licenses._id',
                'spot_licenses.key',
                'spot_licenses.url'
            )
            ->join('spot_licenses', 'product_flat.product_id', '=', 'spot_licenses.product_id')
            ->whereIn('product_flat.product_id', $order_items)
            ->whereIn('spot_licenses.order_id', $orders->pluck('id'))
            ->whereNotNull('spot_id')
            ->get();

        $products = $this->productFlatRepository
            ->getModel()::query()
            ->whereIn('product_id', $order_items->diff($spots->pluck('product_id')))
            ->get();

        $offlineProducts = $products
            ->filter(function ($product) {
                return empty($product->moodle_id) && empty($product->spot_id);
            });

        $moodleProducts =$products
            ->filter(function ($product) {
                return !empty($product->moodle_id);
            });

        $offlineCourses = ProductService::formatProductCard($offlineProducts);

        $enrollments = MoodleService::getUserCourses($this->currentCustomer);
        $moodleCourses = [];
        if ($enrollments) {
            $moodleCourses = MoodleFormatService::formatUserCourses($enrollments, $this->currentCustomer, $moodleProducts);
        }


        $spots = SpotPlayerService::formatProduct($spots, $this->currentCustomer);

        return view('shop::customers.account.my-course.index', compact('offlineCourses', 'moodleCourses', 'spots'));
    }

    public function redirectToCourse()
    {
        if (!auth()->guard('customer')->check()) {
            session()->flash("Unathrozied user");
            redirect()->back();
        }

        $base_url = config("moodle.moodle_address");

        $customer = MoodleService::getCustomLoginURL($this->currentCustomer);

        if (!$customer) {
            $customer = $this->currentCustomer->refresh();
        }
        $moodle_url = $base_url.'/auth/userkey/login.php?key='.$customer->moodle_login_key
            .'&wantsurl='.$base_url.'/course/view.php?id='.request()->course_id;

        return redirect()->to($moodle_url);

    }

    public function redirectToSpotPlayer(SpotLicense $spotLicense)
    {
        if (!auth()->guard('customer')->check()) {
            session()->flash("Unathrozied user");
            redirect()->back();
        }

        $X = \Cookie::get('X');

        //\Cookie::make('X','test',time() + (3600 * 24 * 365 * 100),'/','.laravel.ir', true, false);
        if (!$X || (microtime(true) * 1000) > hexdec(substr($X, 24, 12))) {
            $cookie = Http::withHeaders(['cookie: X='.$X])
                ->get('https://app.spotplayer.ir/')
                ->cookies();
            $x_cookie = $cookie->getCookieByName('X')?->getValue();

            \Cookie::forget('X');
            \Cookie::queue('X', $x_cookie, time() + (3600 * 24 * 365 * 100), '/', config('app.domain'), true, false);
            //\Cookie::make('X',$x_cookie,time() + (3600 * 24 * 365 * 100),'/','bag.laravel.ir', true, false);
            //setcookie('X', $x_cookie, time() + (3600 * 24 * 365 * 100), '/', 'bag.laravel.ir', true, false);
        }

        return view('shop.spotplayer', compact('spotLicense'));

    }
}
