<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use App\Models\SpotLicense;
use App\Services\HttpRequestService;
use App\Services\RouyeshAPIService;
use App\Services\SpotPlayerService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\OrderDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use \Webkul\Sales\Repositories\OrderCommentRepository;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * OrderCommentRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderCommentRepository
     */
    protected $orderCommentRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderCommentRepository  $orderCommentRepository
     *
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderCommentRepository $orderCommentRepository,
        OrderItemRepository $orderItemRepository,
    ) {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderCommentRepository = $orderCommentRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(OrderDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $order = $this->orderRepository->findOrFail($id);

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $result = $this->orderRepository->cancel($id);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }

    /**
     * Complete order
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function complete($id)
    {
        $order = $this->orderRepository
            ->with('items')
            ->find($id);

        if (!$order) {
            session()->flash('error', trans('admin.response.complete-error', ['name' => 'Order']));

            return redirect()->back();
        }
        if (!$order->canComplete()) {
            session()->flash('error', trans('admin.response.complete-error', ['name' => 'Order']));
            return redirect()->back();
        }

        DB::beginTransaction();
        foreach ($order->items as $item) {
            $this->orderItemRepository->update(
                [
                    'product_number' => $item->product->product_number,
                    'rouyesh_code'   => $item->product->rouyesh_code,
                ],
                $item->id
            );

        }
        $this->orderRepository->updateOrderStatus($order, 'completed');
        DB::commit();
        session()->flash('success', trans('app.response.complete-success', ['name' => 'Order']));
        return redirect()->back();

    }

    /**
     * Complete order
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function syncIms($id)
    {
        $order = $this->orderRepository->find($id);
        try {
            $request = new HttpRequestService($order, HttpRequestService::OP_UPDATE_REGISTERATION);
            $response = $request->build();
            if ($response) {
                session()->flash('success', trans('app.response.sync-ims-success', ['name' => 'Order']));
                return redirect()->back();
            }
            session()->flash('error', trans('app.response.sync-ims-fail', ['name' => 'Order']));
            return redirect()->back();
        } catch (\InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        } catch (AuthenticationException $e) {
            session()->flash('error', trans('app.response.sync-ims-unauthorized', ['name' => 'Order']));
            return redirect()->back();
        }

    }

    public function syncRouyesh($id)
    {
        $order = $this->orderRepository->find($id);
        try {
            $request = new RouyeshAPIService($order, HttpRequestService::OP_UPDATE_REGISTERATION);
            $response = $request->build();
            if ($response) {
                session()->flash('success', trans('app.response.sync-rouyesh-success', ['name' => 'Order']));
                return redirect()->back();
            }
            session()->flash('error', trans('app.response.sync-rouyesh-fail', ['name' => 'Order']));
            return redirect()->back();
        } catch (\InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        } catch (AuthenticationException $e) {
            session()->flash('error', trans('app.response.sync-rouyesh-unauthorized', ['name' => 'Order']));
            return redirect()->back();
        }

    }

    public function createSpot($id)
    {
        $order = $this->orderRepository->with('items')->find($id);
        if (!config('app.spot_player.api_key')) {
            session()->flash('error', trans('app.response.create-spot-api-key', ['name' => 'Order']));
            return redirect()->back();
        }
        $license = SpotLicense::query()
            ->where('order_id', $order->id)
            ->where('product_id', $order->items->first()->product_id)
            ->exists();
        if ($license) {
            session()->flash('warning', trans('app.response.spot-exist', ['name' => 'Order']));
            return redirect()->back();
        }
        try {
            $response = SpotPlayerService::generateLicense($order, $order->items->first());

            if ($response) {
                session()->flash('success', trans('app.response.create-spot-success', ['name' => 'Order']));
                return redirect()->back();
            }
            session()->flash('error', trans('app.response.create-spot-fail', ['name' => 'Order']));
            return redirect()->back();
        } catch (\InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        } catch (AuthenticationException $e) {
            session()->flash('error', trans('app.response.create-spot-unauthorized', ['name' => 'Order']));
            return redirect()->back();
        }

    }

    /**
     * Add comment to the order
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function comment($id)
    {
        $data = array_merge(request()->all(), [
            'order_id' => $id,
        ]);

        $data['customer_notified'] = isset($data['customer_notified']) ? 1 : 0;

        Event::dispatch('sales.order.comment.create.before', $data);

        $comment = $this->orderCommentRepository->create($data);

        Event::dispatch('sales.order.comment.create.after', $comment);

        session()->flash('success', trans('admin::app.sales.orders.comment-added-success'));

        return redirect()->back();
    }
}
