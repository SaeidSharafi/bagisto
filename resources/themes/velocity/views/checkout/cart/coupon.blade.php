@if ($cart)
    <script type="text/x-template" id="coupon-component-template">
        <div class="coupon-container pb-2">
            <div class="discount-control">
                <form class="custom-form" method="post" @submit.prevent="applyCoupon">
                    <div class="row no-gutters align-items-stretch">
                        <div class="control-group col-8 col-lg-10" :class="[error_message ? 'has-error' : '']">
                            <input
                                type="text"
                                name="code"
                                class="control coupon-input"
                                v-model="coupon_code"
                                placeholder="{{ __('shop::app.checkout.onepage.enter-coupon-code') }}"/>
                        </div>
                        <div class="col-4 col-lg-2">
                            <button class="btn-coupon theme-btn light p-0 w-100 h-100" :disabled="disable_button">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                        </div>
                    </div>
                    <div class="control-error" v-if="error_message">@{{ error_message }}</div>
                </form>
            </div>

            <div class="applied-coupon-details" v-if="applied_coupon">
                <label>{{ __('shop::app.checkout.total.coupon-applied') }}</label>

                <label class="right" style="display: inline-flex; align-items: center;">
                    <b>@{{ applied_coupon }}</b>

                    <i class="rango-close fs18 cursor-pointer" title="{{ __('shop::app.checkout.total.remove-coupon') }}" v-on:click="removeCoupon"></i>
                </label>
            </div>
        </div>
    </script>

    <script>
        Vue.component('coupon-component', {
            template: '#coupon-component-template',

            inject: ['$validator'],
            props: {
                coupon: null,
                renderFromVue: false,
            },
            data: function () {
                return {
                    coupon_code: '',
                    error_message: '',
                    applied_coupon: "{{ $cart->coupon_code }}",
                    route_name: "{{ request()->route()->getName() }}",
                    disable_button: false,
                }
            },
            mounted() {
                console.log("**************");
                console.log(this.coupon);
                console.log(this.renderFromVue);
                console.log("**************");
                if (this.coupon || this.renderFromVue) {
                    this.applied_coupon = this.coupon;
                }
            },
            watch: {
                coupon_code: function (value) {
                    if (value != '') {
                        this.error_message = '';
                    }
                }
            },

            methods: {
                applyCoupon: function () {
                    if (!this.coupon_code.length) {
                        this.error_message = '{{ __('shop::app.checkout.total.invalid-coupon') }}';

                        return;
                    }

                    this.error_message = null;

                    this.disable_button = true;

                    let code = this.coupon_code;

                    axios
                        .post(
                            '{{ route('shop.checkout.cart.coupon.apply') }}', {code}
                        ).then(response => {
                        if (response.data.success) {
                            console.log("#####");
                            console.log(this.applied_coupon);
                            console.log("#####");
                            this.applied_coupon = this.coupon_code;
                            this.coupon_code = '';

                            window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];

                            this.$root.addFlashMessages();
                            this.$emit('onApplyCoupon', this.applied_coupon);

                            this.redirectIfCartPage();
                        } else {
                            this.error_message = response.data.message;
                        }

                        this.disable_button = false;
                    }).catch(error => {
                        this.error_message = error.response.data.message;

                        this.disable_button = false;
                    });
                },

                removeCoupon: function () {
                    let self = this;

                    axios
                        .delete('{{ route('shop.checkout.coupon.remove.coupon') }}')
                        .then(function (response) {
                            self.$emit('onRemoveCoupon')

                            self.applied_coupon = '';

                            self.disable_button = false;

                            window.flashMessages = [{'type': 'alert-success', 'message': response.data.message}];

                            self.$root.addFlashMessages();

                            self.redirectIfCartPage();
                        })
                        .catch(function (error) {
                            window.flashMessages = [{'type': 'alert-error', 'message': error.response.data.message}];

                            self.$root.addFlashMessages();
                        });
                },

                redirectIfCartPage: function () {
                    if (this.route_name != 'shop.checkout.cart.index') return;

                    setTimeout(function () {
                        window.location.reload();
                    }, 700);
                }
            }
        });
    </script>
@endif