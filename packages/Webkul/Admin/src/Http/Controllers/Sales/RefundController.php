<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Support\Facades\DB;
use Webkul\Admin\DataGrids\OrderRefundDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\RefundRepository;
use DigipayGateway\Services\DeliveryRefundService;
use DigipayGateway\Exceptions\RefundException;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;

    /**
     * Order repository instance.
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Order item repository instance.
     *
     * @var \Webkul\Sales\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * Refund repository instance.
     *
     * @var \Webkul\Sales\Repositories\RefundRepository
     */
    protected $refundRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Webkul\Sales\Repositories\RefundRepository  $refundRepository
     *
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        RefundRepository $refundRepository
    ) {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->refundRepository = $refundRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(OrderRefundDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $orderId
     *
     * @return \Illuminate\Http\View
     */
    public function create($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     *
     * @return \Illuminate\Http\Response
     */
    public function store($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (!$order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.refunds.creation-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'refund.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        $data['refund']['shipping'] = 0;
        $totals['shipping']['price'] = 0;

        $totals = $this->refundRepository->getOrderItemsRefundSummary($data['refund']['items'], $orderId);

        if (!$totals) {
            session()->flash('error', trans('admin::app.sales.refunds.invalid-qty'));

            return redirect()->back();
        }

        $maxRefundAmount = $totals['grand_total']['price'] - $order->refunds()->sum('base_adjustment_refund');

        $refundAmount = $totals['grand_total']['price'] + $data['refund']['adjustment_refund']
            - $data['refund']['adjustment_fee'];

        if (!$refundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.invalid-refund-amount-error'));

            return redirect()->back();
        }

        if ($refundAmount > $maxRefundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.refund-limit-error',
                ['amount' => core()->formatBasePrice($maxRefundAmount)]));

            return redirect()->back();
        }

        DB::beginTransaction();
        $this->refundRepository->create(array_merge($data, ['order_id' => $orderId]));

        // Check if Digipay refund is requested
        if (request()->has('digipay_refund') && request()->get('digipay_refund') == '1') {
            $this->processDigipayRefund($order, (int) $refundAmount);
        }
        DB::commit();

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Refund']));

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Process Digipay refund for an order.
     *
     * @param  \Webkul\Sales\Models\Order  $order
     * @param  int  $amount
     * @return void
     */
    protected function processDigipayRefund($order, int $amount): void
    {
        // Check if this is a Digipay order
        if (!$order->payment || $order->payment->method !== 'digipay') {
            return;
        }

        try {
            $deliveryRefundService = app(DeliveryRefundService::class);
            $response = $deliveryRefundService->refund($order, $amount);

            Log::channel('digipay')->info('[Digipay] Refund via admin panel successful', [
                'order_id' => $order->id,
                'amount' => $amount,
                'tracking_code' => $response->getTrackingCode(),
            ]);

            session()->flash('success', 'بازگشت وجه از دیجی‌پی با موفقیت انجام شد. کد پیگیری: ' . $response->getTrackingCode());
        } catch (RefundException $e) {
            Log::channel('digipay')->error('[Digipay] Refund via admin panel failed', [
                'order_id' => $order->id,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            session()->flash('warning', 'ریفاند ثبت شد اما بازگشت وجه از دیجی‌پی با خطا مواجه شد: ' . $e->getUserMessage());
        } catch (\Throwable $e) {
            Log::channel('digipay')->error('[Digipay] Refund via admin panel unexpected error', [
                'order_id' => $order->id,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            session()->flash('warning', 'ریفاند ثبت شد اما خطای غیرمنتظره در بازگشت وجه از دیجی‌پی: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQty($orderId)
    {
        $data = $this->refundRepository->getOrderItemsRefundSummary(request()->all(), $orderId);

        if (!$data) {
            return response('');
        }

        return response()->json($data);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\View
     */
    public function view($id)
    {
        $refund = $this->refundRepository->findOrFail($id);

        return view($this->_config['view'], compact('refund'));
    }
}
