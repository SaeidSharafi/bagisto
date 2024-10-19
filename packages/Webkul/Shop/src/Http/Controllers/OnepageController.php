<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Models\CartRule;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Payment\Facades\Payment;
use Webkul\Sales\Repositories\OrderRepository;

class OnepageController extends Controller
{
    /**
     * Order repository instance.
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Customer repository instance.
     *
     * @var \Webkul\Customer\Repositories\CustomerRepository
     */
    protected $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     *
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository
    ) {
        $this->orderRepository = $orderRepository;

        $this->customerRepository = $customerRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Event::dispatch('checkout.load.index');

        if (!auth()->guard('customer')->check()
            && !core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout')
        ) {
            session(['url.cart' => url()->current()]);
            return redirect()->route('customer.session.index');
        }

        if (auth()->guard('customer')->check()) {

            if (auth()->guard('customer')->user()->is_suspended) {
                session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

                return redirect()->route('shop.home.index');
            }

            if (auth()->guard('customer')->user()->incomplete) {
                return redirect()->route('customer.profile.edit')
                    ->with('warning', "لطفا پروفایل خود را تکمیل نمایید");
            }

        }

        if (Cart::hasError()) {
            return redirect()->route('shop.home.index');
        }

        $cart = Cart::getCart();

        if (
            (!auth()->guard('customer')->check() && $cart->hasDownloadableItems())
            || (!auth()->guard('customer')->check() && !$cart->hasGuestCheckoutItems())
        ) {
            return redirect()->route('customer.session.index');
        }

        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ??
            0;

        if (!$cart->checkMinimumOrder()) {
            session()->flash('warning', trans('shop::app.checkout.cart.minimum-order-message',
                ['amount' => core()->currency($minimumOrderAmount)]));

            return redirect()->back();
        }

        Cart::collectTotals();
        if ($cart->base_grand_total == 0) {
            \Log::info("base_grand_total is 0 ");
            Cart::savePaymentMethod(['method' => 'mellat']);
            $order = $this->orderRepository->create(Cart::prepareDataForOrder());
            $this->orderRepository->updateOrderStatus($order, 'processing');
            $order->refresh();
            //$order = $this->orderRepository->update(['status' => 'processing'],
            //    $order->id);
            session()->flash('order', $order);
            return redirect()->route('shop.checkout.success');
        }
        $coupons = CartRule::query()
            ->with('cart_rule_coupon')
            ->where('show_in_list', 1)
            ->get()
            ->pluck('name', 'coupon_code')
            ->toArray();

        return view($this->_config['view'], compact('cart', 'coupons'));
    }

    /**
     * Return order short summary.
     *
     * @return \Illuminate\Http\Response
     */
    public function summary()
    {
        $cart = Cart::getCart();
        $coupons = CartRule::query()
            ->with('cart_rule_coupon')
            ->where('show_in_list', 1)
            ->get()
            ->pluck('name', 'coupon_code')
            ->toArray();
        return response()->json([
            'html' => view('shop::checkout.total.summary', compact('cart','coupons'))->render(),
        ]);
    }

    /**
     * Saves customer address.
     *
     * @param  \Webkul\Checkout\Http\Requests\CustomerAddressForm  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = $request->all();

        if (!auth()->guard('customer')->check() && !Cart::getCart()->hasGuestCheckoutItems()) {
            return response()->json(['redirect_url' => route('customer.session.index')], 403);
        }

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (Cart::hasError() || !Cart::saveCustomerAddress($data)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        $cart = Cart::getCart();

        Cart::collectTotals();

        //if ($cart->haveStockableItems()) {
        //    if (! $rates = Shipping::collectRates()) {
        //        return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        //    }
        //
        //    return response()->json($rates);
        //}

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Get Payment methods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentMethods()
    {
        Cart::collectTotals();

        $cart = Cart::getCart();
        return response()->json(
            array_merge(Payment::getSupportedPaymentMethods(),
                ['review_html' => view('shop::checkout.onepage.review', compact('cart'))->render()])
        );
    }

    /**
     * Saves shipping method.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveShipping()
    {
        $shippingMethod = request()->get('shipping_method');

        if (Cart::hasError() || !$shippingMethod || !Cart::saveShippingMethod($shippingMethod)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Saves payment method.
     *
     * @return \Illuminate\Http\Response
     */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if (Cart::hasError() || !$payment || !Cart::savePaymentMethod($payment)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'jump_to_section' => 'checkout',
            'html'            => view('shop::checkout.onepage.checkout', compact('cart'))->render(),
        ]);
    }

    /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveOrder()
    {
        if (Cart::hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $this->validateOrder();

        $cart = Cart::getCart();

        if ($cart->grand_total != 0) {
            if ($redirectUrl = Payment::getRedirectUrl($cart)) {
                return response()->json([
                    'success'      => true,
                    'redirect_url' => $redirectUrl,
                ]);
            }
        }

        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        $this->orderRepository->updateOrderStatus($order,'processing');

        Cart::deActivateCart();

        Cart::activateCartIfSessionHasDeactivatedCartId();

        session()->flash('order', $order);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Order success page.
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        if (!$order = session('order')) {
            return redirect()->route('shop.checkout.cart.index');
        }

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if (auth()->guard('customer')->check() && auth()->guard('customer')->user()->is_suspended) {
            throw new \Exception(trans('shop::app.checkout.cart.suspended-account-message'));
        }

        if (!$cart->checkMinimumOrder()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message',
                ['amount' => core()->currency($minimumOrderAmount)]));
        }

        //if ($cart->haveStockableItems() && ! $cart->shipping_address) {
        //    throw new \Exception(trans('shop::app.checkout.cart.check-shipping-address'));
        //}
        //
        //if (! $cart->billing_address) {
        //    throw new \Exception(trans('shop::app.checkout.cart.check-billing-address'));
        //}
        //
        //if ($cart->haveStockableItems() && ! $cart->selected_shipping_rate) {
        //    throw new \Exception(trans('shop::app.checkout.cart.specify-shipping-method'));
        //}

        if (!$cart->payment) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-payment-method'));
        }
    }

    /**
     * Check customer is exist or not.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkExistCustomer()
    {
        $customer = $this->customerRepository->findOneWhere([
            'email' => request()->email,
        ]);

        if (!is_null($customer)) {
            return 'true';
        }

        return 'false';
    }

    /**
     * Login for checkout.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginForCheckout()
    {
        $this->validate(request(), [
            'email' => 'required|email',
        ]);

        if (!auth()->guard('customer')->attempt(request(['email', 'password']))) {
            return response()->json(['error' => trans('shop::app.customer.login-form.invalid-creds')]);
        }

        Cart::mergeCart();

        return response()->json(['success' => 'Login successfully']);
    }

    /**
     * To apply couponable rule requested.
     *
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon()
    {
        $this->validate(request(), [
            'code' => 'string|required',
        ]);

        $code = request()->input('code');

        $result = $this->coupon->apply($code);

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('shop::app.checkout.total.coupon-applied'),
                'result'  => $result,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => trans('shop::app.checkout.total.cannot-apply-coupon'),
            'result'  => null,
        ], 422);
    }

    /**
     * Initiates the removal of couponable cart rule.
     *
     * @return array
     */
    public function removeCoupon()
    {
        $result = $this->coupon->remove();

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('admin::app.promotion.status.coupon-removed'),
                'data'    => [
                    'grand_total' => core()->currency(Cart::getCart()->grand_total),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => trans('admin::app.promotion.status.coupon-remove-failed'),
            'data'    => null,
        ], 422);
    }

    /**
     * Check for minimum order.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkMinimumOrder()
    {
        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ??
            0;

        $status = Cart::checkMinimumOrder();

        return response()->json([
            'status'  => !$status ? false : true,
            'message' => !$status ? trans('shop::app.checkout.cart.minimum-order-message',
                ['amount' => core()->currency($minimumOrderAmount)]) : 'Success',
        ]);
    }
}
