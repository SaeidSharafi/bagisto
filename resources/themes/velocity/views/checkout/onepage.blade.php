@extends('shop::layouts.master') @section('page_title')
{{ __("shop::app.checkout.onepage.title") }}
@stop @section('content-wrapper')
<checkout></checkout>
@endsection @push('scripts')
<script
  type="text/javascript"
  src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"
></script>

@include('shop::checkout.cart.coupon')
<script type="text/x-template" id="checkout-template">
  <div class="container">
      <div id="checkout" class="checkout-process row">
          <h1 class="col-12">{{ __('velocity::app.checkout.checkout') }}</h1>
          <div class="col-12">
            <p class="foot-note pt-3 text-start font-weight-bold">
                ثبت نام شما در این دوره به معنای پذیرش
                <a href="/page/terms-conditions" class="">شرایط جهاد دانشگاهی قزوین</a>
                است
            </p>
        </div>
          <div class="col-lg-7 col-md-12">
              <div
                  class="step-content review"
                  v-if="showSummarySection"
                  id="summary-section">

                  <review-section :key="reviewComponentKey">

                  </review-section>

              </div>
              <div
                  v-if="showPaymentSection"
                  class="step-content payment"
                  id="payment-section">

                  <payment-section @onPaymentMethodSelected="paymentMethodSelected($event)">
                  </payment-section>


              </div>
              <div
                  v-if="showCheckoutSection"
                  class="step-content checkout"
                  id="checkout-section">

                  <checkout-section :key="checkoutComponentKey">
                      <div slot="place-order-btn">
                          <div class="mb20">
                              <button
                                  type="button"
                                  class="theme-btn"
                                  @click="placeOrder()"
                                  :disabled="!isPlaceOrderEnabled"
                                  v-if="selected_payment_method.method != 'paypal_smart_button'"
                                  id="checkout-place-order-button">
                                  {{ __('shop::app.checkout.onepage.place-order') }}
                              </button>
                          </div>
                      </div>
                  </checkout-section>


              </div>

          </div>

          <div class="col-lg-4 col-md-12 order-summary-container mr-0 top pt0">
              <summary-section
                  discount="1"
                  :key="summaryComponentKey">
                  <div slot="coupon">
                      <coupon-component
                          :coupon="current_coupon"
                          :render-from-vue="render_from_vue"
                          @onApplyCoupon="ApplyCoupon"
                          @onRemoveCoupon="RemoveCoupon"></coupon-component>
                  </div>

              </summary-section>

              <div class="paypal-button-container mt10"></div>
          </div>
      </div>
  </div>
</script>

<script type="text/javascript">
  (() => {
      var reviewHtml = '';
      var paymentHtml = '';
      var summaryHtml = '';
      var checkoutHtml = '';
      var shippingHtml = '';
      var paymentMethods = '';
      var customerAddress = '';
      var compeltedRegistration = false;
      var shippingMethods = '';

      var reviewTemplateRenderFns = [];
      var paymentTemplateRenderFns = [];
      var summaryTemplateRenderFns = [];
      var checkoutTemplateRenderFns = [];
      var shippingTemplateRenderFns = [];

      //TODO use this to check for extra info
      @auth('customer')
          @if(auth('customer')->user()->first_name && auth('customer')->user()->last_name)
          compeltedRegistration = true;
      @endif
      @endauth

      Vue.component('checkout', {
          template: '#checkout-template',
          inject: ['$validator'],

          data: function () {
              return {
                  allAddress: {},
                  current_step: 1,
                  completed_step: 0,
                  isCheckPayment: true,
                  is_customer_exist: 0,
                  disable_button: false,
                  shippingComponentKey: 0,
                  reviewComponentKey: 0,
                  summaryComponentKey: 0,
                  checkoutComponentKey: 0,
                  showPaymentSection: false,
                  showSummarySection: true,
                  showCheckoutSection: true,
                  isPlaceOrderEnabled: false,
                  new_billing_address: false,
                  showShippingSection: false,
                  new_shipping_address: false,
                  render_from_vue: false,
                  current_coupon: '',
                  selected_payment_method: '',
                  selected_shipping_method: '',
                  countries: [],
                  countryStates: [],

                  step_numbers: {
                      'review': 1,
                      'payment': 2,
                      'checkout': 3,

                  },

                  address: {
                      billing: {
                          address1: [''],
                          save_as_address: false,
                          use_for_shipping: true,
                      },

                      shipping: {
                          address1: ['']
                      },
                  },
              }
          },

          created: function () {


              this.getOrderSummary();

              this.$http.get("{{ route('shop.checkout.get-pyaments') }}")
                  .then(response => {
                      this.disable_button = false;
                      this.isPlaceOrderEnabled = true;

                      paymentHtml = Vue.compile(response.data.html);
                      reviewHtml = Vue.compile(response.data.review_html);

                      this.completed_step = this.step_numbers[response.data.jump_to_section] + 1;
                      this.current_step = this.step_numbers[response.data.jump_to_section];
                      this.showSummarySection = true;
                      this.showPaymentSection = true;
                      this.getOrderSummary();

                      this.$root.hideLoader();
                  })
                  .catch(error => {
                      this.disable_button = false;
                      this.$root.hideLoader();

                      this.handleErrorResponse(error.response, 'address-form')
                  });
          },

          methods: {
              navigateToStep: function (step) {
                  if (step <= this.completed_step) {
                      this.current_step = step;
                      this.completed_step = step - 1;
                  }
              },


              validateForm: async function (scope) {
                  var isManualValidationFail = false;


                  if (!isManualValidationFail) {
                      await this.$validator.validateAll(scope)
                          .then(result => {
                              if (result) {
                                  switch (scope) {
                                      case 'payment-form':
                                          this.$root.showLoader();
                                          this.savePayment();

                                          this.isPlaceOrderEnabled = true;
                                          break;

                                      default:
                                          break;
                                  }

                              } else {
                                  this.isPlaceOrderEnabled = false;
                              }
                          });
                  } else {
                      this.isPlaceOrderEnabled = false;
                  }
              },


              isCustomerExist: function () {
                  this.$validator.attach('address-form.billing[email]', 'required|email');

                  this.$validator.validate('address-form.billing[email]', this.address.billing.email)
                      .then(isValid => {
                          if (!isValid)
                              return;

                          this.$http.post("{{ route('customer.checkout.exist') }}", {email: this.address.billing.email})
                              .then(response => {
                                  this.is_customer_exist = response.data ? 1 : 0;

                                  if (response.data)
                                      this.$root.hideLoader();
                              })
                              .catch(function (error) {
                              })
                      })
                      .catch(error => {
                      })
              },

              loginCustomer: function () {
                  this.$http.post("{{ route('customer.checkout.login') }}", {
                      email: this.address.billing.email,
                      password: this.address.billing.password
                  })
                      .then(response => {
                          if (response.data.success) {
                              window.location.href = "{{ route('shop.checkout.onepage.index') }}";
                          } else {
                              window.showAlert(`alert-danger`, this.__('shop.general.alert.danger'), response.data.error);
                          }
                      })
                      .catch(function (error) {
                      })
              },

              getOrderSummary: function () {
                  this.$http.get("{{ route('shop.checkout.summary') }}")
                      .then(response => {
                          summaryHtml = Vue.compile(response.data.html)

                          this.summaryComponentKey++;
                          this.reviewComponentKey++;
                          this.checkoutComponentKey++;
                      })
                      .catch(function (error) {
                      })
              },

              savePayment: async function () {
                  this.disable_button = true;

                  if (this.isCheckPayment) {
                      this.isCheckPayment = false;

                      this.$http.post("{{ route('shop.checkout.save-payment') }}", {'payment': this.selected_payment_method})
                          .then(response => {
                              this.isCheckPayment = true;
                              this.disable_button = false;

                              this.showSummarySection = true;
                              this.$root.hideLoader();
                              checkoutHtml = Vue.compile(response.data.html);
                              this.showCheckoutSection = true;
                              console.log(checkoutHtml);
                              console.log(response);
                              this.completed_step = this.step_numbers[response.data.jump_to_section] + 1;
                              this.current_step = this.step_numbers[response.data.jump_to_section];

                              document.body.style.cursor = 'auto';

                              this.getOrderSummary();
                          })
                          .catch(error => {
                              this.disable_button = false;
                              this.$root.hideLoader();
                              this.handleErrorResponse(error.response, 'payment-form')
                          });
                  }
              },

              placeOrder: async function () {
                  if (this.isPlaceOrderEnabled) {
                      this.disable_button = false;
                      this.isPlaceOrderEnabled = false;

                      this.$root.showLoader();

                      this.$http.post("{{ route('shop.checkout.save-order') }}", {'_token': "{{ csrf_token() }}"})
                          .then(response => {
                              if (response.data.success) {
                                  if (response.data.redirect_url) {
                                      this.$root.hideLoader();
                                      window.location.href = response.data.redirect_url;
                                  } else {
                                      this.$root.hideLoader();
                                      window.location.href = "{{ route('shop.checkout.success') }}";
                                  }
                              }
                          })
                          .catch(error => {
                              this.disable_button = true;
                              this.$root.hideLoader();

                              window.showAlert(`alert-danger`, this.__('shop.general.alert.danger'), "{{ __('shop::app.common.error') }}");
                          })
                  } else {
                      this.disable_button = true;
                  }
              },
              ApplyCoupon: function (coupon) {
                  // console.log("changing")
                  // console.log(coupon)
                  this.current_coupon = coupon;
                  this.render_from_vue = true;
                  this.getOrderSummary();
              },
              RemoveCoupon: function () {
                  // console.log("changing")
                  this.current_coupon = null;
                  this.render_from_vue = true;
                  this.getOrderSummary();
              },
              handleErrorResponse: function (response, scope) {
                  if (response.status == 422) {
                      serverErrors = response.data.errors;
                      this.$root.addServerErrors(scope)
                  } else if (response.status == 403) {
                      if (response.data.redirect_url) {
                          window.location.href = response.data.redirect_url;
                      }
                  }
              },

              shippingMethodSelected: function (shippingMethod) {
                  this.selected_shipping_method = shippingMethod;
              },

              paymentMethodSelected: function (paymentMethod) {
                  this.selected_payment_method = paymentMethod;
              },

              newBillingAddress: function () {
                  this.new_billing_address = true;
                  this.isPlaceOrderEnabled = false;
                  this.address.billing.address_id = null;
              },

              newShippingAddress: function () {
                  this.new_shipping_address = true;
                  this.isPlaceOrderEnabled = false;
                  this.address.shipping.address_id = null;
              },

              backToSavedBillingAddress: function () {
                  this.new_billing_address = false;
                  this.validateFormAfterAction()
              },

              backToSavedShippingAddress: function () {
                  this.new_shipping_address = false;
                  this.validateFormAfterAction()
              },

              validateFormAfterAction: function () {
                  setTimeout(() => {
                      this.validateForm('address-form');
                  }, 0);
              }
          }
      });

      Vue.component('shipping-section', {
          inject: ['$validator'],

          data: function () {
              return {
                  templateRender: null,

                  selected_shipping_method: '',

                  first_iteration: true,
              }
          },

          staticRenderFns: shippingTemplateRenderFns,

          mounted: function () {
              this.templateRender = shippingHtml.render;

              for (var i in shippingHtml.staticRenderFns) {
                  shippingTemplateRenderFns.push(shippingHtml.staticRenderFns[i]);
              }

              eventBus.$emit('after-checkout-shipping-section-added');
          },

          render: function (h) {
              return h('div', [
                  (this.templateRender ?
                      this.templateRender() :
                      '')
              ]);
          },

          methods: {
              methodSelected: function () {
                  this.$parent.validateForm('shipping-form');

                  this.$emit('onShippingMethodSelected', this.selected_shipping_method)

                  eventBus.$emit('after-shipping-method-selected', this.selected_shipping_method);
              }
          }
      })

      Vue.component('payment-section', {
          inject: ['$validator'],

          data: function () {
              return {
                  templateRender: null,

                  payment: {
                      method: ""
                  },

                  first_iteration: true,
              }
          },

          staticRenderFns: paymentTemplateRenderFns,

          mounted: function () {

              this.templateRender = paymentHtml.render;
              for (var i in paymentHtml.staticRenderFns) {
                  paymentTemplateRenderFns.push(paymentHtml.staticRenderFns[i]);
              }

              eventBus.$emit('after-checkout-payment-section-added');
          },

          render: function (h) {
              return h('div', [
                  (this.templateRender ?
                      this.templateRender() :
                      '')
              ]);
          },

          methods: {
              methodSelected: function () {
                  this.$parent.validateForm('payment-form');

                  this.$emit('onPaymentMethodSelected', this.payment)

                  eventBus.$emit('after-payment-method-selected', this.payment);
              }
          }
      })

      Vue.component('review-section', {
          data: function () {
              return {
                  error_message: '',
                  templateRender: null,
              }
          },

          staticRenderFns: reviewTemplateRenderFns,

          render: function (h) {
              return h(
                  'div', [
                      this.templateRender ? this.templateRender() : ''
                  ]
              );
          },

          mounted: function () {
              this.templateRender = reviewHtml.render;

              for (var i in reviewHtml.staticRenderFns) {
                  reviewTemplateRenderFns[i] = reviewHtml.staticRenderFns[i];
              }

              this.$forceUpdate();
          }
      });

      Vue.component('checkout-section', {
          data: function () {
              return {
                  error_message: '',
                  templateRender: null,
              }
          },

          staticRenderFns: checkoutTemplateRenderFns,

          render: function (h) {
              return h(
                  'div', [
                      this.templateRender ? this.templateRender() : ''
                  ]
              );
          },

          mounted: function () {
              this.templateRender = checkoutHtml.render;

              for (var i in checkoutHtml.staticRenderFns) {
                  checkoutTemplateRenderFns[i] = checkoutHtml.staticRenderFns[i];
              }

              this.$forceUpdate();
          }
      });

      Vue.component('summary-section', {
          inject: ['$validator'],

          staticRenderFns: summaryTemplateRenderFns,

          props: {
              discount: {
                  default: 0,
                  type: [String, Number],
              }
          },

          data: function () {
              return {
                  changeCount: 0,
                  coupon_code: null,
                  error_message: null,
                  templateRender: null,
                  couponChanged: false,
              }
          },

          mounted: function () {
              this.templateRender = summaryHtml.render;
              for (var i in summaryHtml.staticRenderFns) {
                  summaryTemplateRenderFns[i] = summaryHtml.staticRenderFns[i];
              }

              this.$forceUpdate();
          },

          render: function (h) {
              return h('div', [
                  (this.templateRender ?
                      this.templateRender() :
                      '')
              ]);
          },

          methods: {
              onSubmit: function () {
                  var this_this = this;
                  const emptyCouponErrorText = "Please enter a coupon code";
              },

              changeCoupon: function () {
                  if (this.couponChanged == true && this.changeCount == 0) {
                      this.changeCount++;

                      this.error_message = null;

                      this.couponChanged = false;
                  } else {
                      this.changeCount = 0;
                  }
              },
          }
      });

  })();
</script>
@endpush
