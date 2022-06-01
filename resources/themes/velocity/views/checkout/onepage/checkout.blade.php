<div class="order-description row fs16 cart-details">
    <div class="col-lg-4 col-md-12">


        <div class="payment mb20">
            <div class="decorator">
                <i class="icon payment-icon"></i>
            </div>

            <div class="text">
                <h4 class="fw6 fs18">
                    {{ core()->getConfigData('sales.paymentmethods.' . $cart->payment->method . '.title') }}
                </h4>

                <span>{{ __('shop::app.customer.account.order.view.payment-method') }}</span>
            </div>
        </div>

        <slot name="place-order-btn"></slot>
    </div>

</div>