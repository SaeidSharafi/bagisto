<?php

namespace PayIr\Http\Controllers;

use PayIr\Exceptions\SendException;
use PayIr\Exceptions\VerifyException;
use PayIr\Helpers\Request;
use PayIr\Payment\PayIr;
use PayIr\Services\PayIrService;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;

class PayDotIrController extends Controller
{
    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    protected $payIr;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Paypal\Helpers\Ipn  $ipnHelper
     *
     * @return void
     */
    public function __construct(
        PayIrService $payIrService
    ) {
        $this->payIrService = $payIrService;
    }

    /**
     * Redirects to the paypal.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        try {
            $send = $this->payIrService->send();
            $paymentUrl = $send['payment_url'];
        } catch (SendException $e) {
            session()->flash('error', 'Cannot connect to gateway.');
            return redirect()->route('shop.checkout.cart.index');
        }
        return redirect($paymentUrl);

        //return view('payir::standard-redirect');
    }

    /**
     * Cancel payment from paypal.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        session()->flash('error', 'Paypal payment has been canceled.');

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
        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Success payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {

        if (!Cart::getCart()){
            return redirect()->route('shop.checkout.cart.index');
        }
        $request = \request()->all();

        if ($request['status'] === "0"){
            return redirect()->route('paydotir.cancel');
        }

        try {
            $order = $this->payIrService->processCart($request);
        } catch (VerifyException $e) {
            return redirect()->route('paydotir.failed');
        }

        Cart::deActivateCart();

        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }
}