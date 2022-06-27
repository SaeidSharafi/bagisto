@if ($cart)
    <script type="text/x-template" id="coupon-component-template">
        <div class="coupon-container pb-2">
            <div class="discount-control">
                <form class="custom-form" method="post" @submit.prevent="applyCoupon">
                    <div class="control-group mb-2" v-if="rules.length > 0">
                        <label for="channels" >{{ __('app.promotions.cart-rules.list') }}</label>

                        <select class="control"  v-model="coupon_code_list" id="coupon_code_list" name="coupon_code_list">
                            <option value="" selected>انتخاب کنید</option>
                            <option :value="key" v-for="(rule,key) in rules" v-bind="key">
                                @{{ rule }}
                            </option>


                        </select>
                    </div>
                    <div class="row no-gutters align-items-stretch">
                        <div class="control-group col-8 col-lg-10" :class="[error_message ? 'has-error' : '']">
                            <input
                                :disabled="matched"
                                type="text"
                                name="code"
                                :class="`control coupon-input ${matched ? 'disabled' : ''}`"
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

                    <b v-if="matched">@{{ rules[applied_coupon] }}</b>
                    <b v-else>@{{ applied_coupon }}</b>

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
                    selected_coupon: '{{ $from_list }}',
                    coupon_code_list: '',
                    rules: @json($coupons),
                    route_name: "{{ request()->route()->getName() }}",
                    disable_button: false,
                }
            },
            mounted() {
                console.log("**************");
                console.log(this.applied_coupon);
                console.log(this.selected_coupon);
                console.log(this.rules);
                console.log(this.coupon_code_list);
                // console.log(this.renderFromVue);
                console.log("**************");
                if (this.coupon || this.renderFromVue) {
                    this.applied_coupon = this.coupon;
                }
            },
            computed:{
                matched(){

                    return this.rules[this.applied_coupon];
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

                    if (!this.coupon_code.length && !this.coupon_code_list.length) {
                        this.error_message = '{{ __('shop::app.checkout.total.invalid-coupon') }}';

                        return;
                    }

                    this.error_message = null;

                    this.disable_button = true;


                    let code = !(this.coupon_code_list.length) ? this.coupon_code :this.coupon_code_list;
                    this.coupon_code_list = '';
                    axios
                        .post(
                            '{{ route('shop.checkout.cart.coupon.apply') }}', {code}
                        ).then(response => {
                        if (response.data.success) {
                            // console.log("#####");
                             console.log(this.applied_coupon);
                            // console.log("#####");
                            this.applied_coupon = code;
                            this.selected_coupon = code;
                            this.coupon_code = '';
                            // this.coupon_code_list = '';

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
                            // self.coupon_code_list = '';

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