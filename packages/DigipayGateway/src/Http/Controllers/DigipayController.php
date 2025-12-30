<?php

declare(strict_types=1);

namespace DigipayGateway\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use DigipayGateway\Services\PaymentOrchestrator;
use DigipayGateway\DataTransferObjects\CallbackPayload;
use DigipayGateway\Exceptions\DigipayException;
use Illuminate\Support\Facades\Log;

class DigipayController extends Controller
{
    private PaymentOrchestrator $paymentOrchestrator;

    public function __construct(PaymentOrchestrator $paymentOrchestrator)
    {
        $this->paymentOrchestrator = $paymentOrchestrator;
    }

    /**
     * Initiate payment and redirect to gateway.
     *
     * @return View|RedirectResponse
     */
    public function redirect()
    {
        try {
            $result = $this->paymentOrchestrator->initiatePayment();

            return view('digipay::redirect', [
                'redirect_url' => $result['redirect_url'],
                'order_id' => $result['order_id'],
            ]);
        } catch (DigipayException $e) {
            Log::error('[Digipay] Redirect failed', [
                'error' => $e->getMessage(),
                'code' => $e->getErrorCode(),
                'context' => $e->getContext(),
            ]);

            session()->flash('error', $e->getUserMessage());

            return redirect()->route('shop.checkout.cart.index');
        } catch (\Exception $e) {
            Log::error('[Digipay] Unexpected error during redirect', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', __('digipay::messages.unexpected_error'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle callback from Digipay after payment.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function callback(Request $request): RedirectResponse
    {
        Log::info('[Digipay] Callback received', $request->all());

        try {
            $payload = CallbackPayload::fromRequest($request->all());

            $result = $this->paymentOrchestrator->handleCallback($payload);

            if ($result['success']) {
                // Deactivate cart and store order in session
                \Webkul\Checkout\Facades\Cart::deActivateCart();

                session()->flash('order', $result['order']);
                session()->flash('success', $result['message']);

                return redirect()->route('shop.checkout.success');
            }

            // Payment failed - deactivate cart and show order page with error
            \Webkul\Checkout\Facades\Cart::deActivateCart();
            session()->flash('error', $result['message']);

            return redirect()->route('customer.orders.view', $result['order_id']);
        } catch (DigipayException $e) {
            Log::error('[Digipay] Callback processing failed', [
                'error' => $e->getMessage(),
                'code' => $e->getErrorCode(),
                'context' => $e->getContext(),
            ]);

            session()->flash('error', $e->getUserMessage());

            return redirect()->route('shop.checkout.cart.index');
        } catch (\Exception $e) {
            Log::error('[Digipay] Unexpected error during callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', __('digipay::messages.unexpected_error'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle user cancellation.
     *
     * @return RedirectResponse
     */
    public function cancel(): RedirectResponse
    {
        session()->flash('warning', __('digipay::messages.payment_cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle payment failure.
     *
     * @return RedirectResponse
     */
    public function failed(): RedirectResponse
    {
        session()->flash('error', __('digipay::messages.payment_failed'));

        return redirect()->route('shop.checkout.cart.index');
    }
}
