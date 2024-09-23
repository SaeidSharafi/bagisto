<?php

namespace ACECRGateway\Http\Controllers;

use ACECRGateway\Exceptions\SendException;
use ACECRGateway\Exceptions\SettleException;
use ACECRGateway\Exceptions\VerifyException;
use ACECRGateway\Services\ACECRService;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;

class ACECRController extends Controller
{
    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    protected $acecrService;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Paypal\Helpers\Ipn  $ipnHelper
     *
     * @return void
     */
    public function __construct(
        ACECRService $acecrService
    ) {
        $this->acecrService = $acecrService;
    }

    /**
     * Redirects to the paypal.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        try {
            $send = $this->acecrService->send();
            $paymentUrl = $send['payment_url'];
            $refID = $send['refID'];
        } catch (SendException $e) {
            session()->flash('error', 'خطا در ارتباط با درگاه بانکی');
            Log::info($e->getMessage());
            return redirect()->route('customer.orders.index');
        }
        //return redirect($paymentUrl);

        return view('acecr::standard-redirect')->with(['payment_url' => $paymentUrl, 'refID' => $refID]);
    }

    /**
     * Cancel payment from paypal.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        session()->flash('error', 'پرداخت ناموفق');

        return redirect()->route('customer.orders.index');
    }

    /**
     * Cancel payment from paypal.
     *
     * @return \Illuminate\Http\Response
     */
    public function failed()
    {
        session()->flash('error', 'Payment verification failed');
        Cart::deActivateCart();
        return redirect()->route('customer.orders.index');
    }

    /**
     * Success payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        \Log::info("request ", \request()->all());

        $request = \request()->all();

        if ($request['ResCode'] != "0") {
            $order = $this->acecrService->cancelOrder($request);
            \Log::info("canceled order , ID".$order->id);
            session()->flash('error', 'پرداخت ناموفق');
            Cart::deActivateCart();
            return redirect()->route('customer.orders.view', $request['SaleOrderId']);
        }

        try {
            $order = $this->acecrService->verifyOrder($request);
            \Log::info("verified order ,ID ".$order->id);
        } catch (VerifyException|SettleException|SendException $e) {
            Cart::deActivateCart();
            if ($e->orderId) {
                \Log::warning($e->getMessage());
                session()->flash('error', 'خطا در تایید تراکنش');
                return redirect()->route('customer.orders.view', $request['SaleOrderId']);
            }
        }
        Cart::deActivateCart();
        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }
}