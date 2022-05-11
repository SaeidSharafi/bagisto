<?php

namespace MellatGateway\Http\Controllers;

use MellatGateway\Exceptions\SendException;
use MellatGateway\Exceptions\SettleException;
use MellatGateway\Exceptions\VerifyException;
use MellatGateway\Services\MellatService;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;

class MellatController extends Controller
{
    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    protected $mellatService;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Paypal\Helpers\Ipn  $ipnHelper
     *
     * @return void
     */
    public function __construct(
        MellatService $mellatService
    ) {
        $this->mellatService = $mellatService;
    }

    /**
     * Redirects to the paypal.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        try {
            $send = $this->mellatService->send();
            $paymentUrl = $send['payment_url'];
            $refID = $send['refID'];
        } catch (SendException $e) {
            session()->flash('error', $e->getErrorMessage());
            return redirect()->route('shop.checkout.cart.index');
        }
        //return redirect($paymentUrl);

        return view('mellat::standard-redirect')->with(['payment_url' => $paymentUrl, 'refID' => $refID]);
    }

    /**
     * Cancel payment from paypal.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        session()->flash('error', 'پرداخت ناموفق');

        return redirect()->route('shop.checkout.cart.index');
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
        return redirect()->route('order');
    }

    /**
     * Success payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        \Log::info("request ", \request()->all());
        if (!Cart::getCart()) {
            return redirect()->route('shop.checkout.cart.index');
        }
        $request = \request()->all();

        if ($request['ResCode'] != "0") {
            return redirect()->route('mellat.cancel');
        }

        try {
            $order = $this->mellatService->processCart($request);
        } catch (VerifyException|SettleException|SendException $e) {
            if ($e->orderId) {
                session()->flash('error', 'خطا در تایید تراکنش');
                return redirect()->route('customer.orders.view', $e->orderId);
            }
            return redirect()->route('mellat.cancel');
        }

        Cart::deActivateCart();

        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }
}