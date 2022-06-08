<?php

namespace App\Http\Controllers;

use App\Services\MoodleFormatService;
use App\Services\MoodleService;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Sales\Repositories\OrderRepository;

class MoodleController extends Controller
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
            ->where('status', 'completed')
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('product_id');
        $products = $this->productFlatRepository
            ->getModel()::whereIn('id', $orders)
            ->whereNotNull('moodle_id')
            ->get();

        $customer = MoodleService::getLoginURL($this->currentCustomer);
        if (!$customer) {
            $customer = $this->currentCustomer->refresh();
        }

        $enrollments = MoodleService::getUserCourses($customer);
        if ($enrollments) {
            $enrollments = MoodleFormatService::formatUserCourses($enrollments, $customer, $products);
        }

        $products = MoodleFormatService::formatProduct($products, $customer);


        return view('shop::customers.account.moodle.index', compact('products', 'enrollments'));
    }
}