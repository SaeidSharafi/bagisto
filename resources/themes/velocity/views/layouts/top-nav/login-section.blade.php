{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}

    <div class="dropdown">
        <div id="account" class="account-menu d-flex align-items-center">
            @auth('customer')
            <div class="d-inline-block welcome-content" @click="togglePopup">
                <i class="fa-user far px-1"></i>
                <i class="fa fa-caret-down"></i>
            </div>
            @endauth
        </div>

        <div id="account-modal" class="account-modal sensitive-modal hide mt5">
            @guest('customer')
                <div class="modal-content">
                    <div class="modal-header no-border pb0">
                        <label class="fs18 grey">{{ __('shop::app.header.title') }}</label>

                        <button type="button" class="close disable-box-shadow" data-dismiss="modal" aria-label="Close" @click="togglePopup">
                            <span aria-hidden="true" class="white-text fs20">×</span>
                        </button>
                    </div>

                    <div class="pl10 fs14">
                        <p>{{ __('shop::app.header.dropdown-text') }}</p>
                    </div>

                    <div class="modal-footer">
                        <div>
                            <a href="{{ route('customer.auth.create') }}">
                                <button
                                    type="button"
                                    class="theme-btn fs14 fw6">

                                    {{ __('shop::app.header.sign-in') }}
                                </button>
                            </a>
                        </div>

                    </div>
                </div>
            @endguest

            @auth('customer')
                <div class="modal-content customer-options">
                    <div class="customer-session">
                        <label class="">
                            {{ auth()->guard('customer')->user()->first_name }}
                        </label>
                    </div>

                    <ul type="none">
                        <li>
                            <a href="{{ route('customer.profile.index') }}" class="unset">{{ __('shop::app.header.profile') }}</a>
                        </li>

                        <li>
                            <a href="{{ route('customer.orders.index') }}" class="unset">{{ __('velocity::app.shop.general.orders') }}</a>
                        </li>

                        @php
                            $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;

                            $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                        @endphp

                        @if ($showWishlist)
                            <li>
                                <a href="{{ route('customer.wishlist.index') }}" class="unset">{{ __('shop::app.header.wishlist') }}</a>
                            </li>
                        @endif

                        @if ($showCompare)
                            <li>
                                <a href="{{ route('velocity.customer.product.compare') }}" class="unset">{{ __('velocity::app.customer.compare.text') }}</a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('customer.session.destroy') }}" class="unset">{{ __('shop::app.header.logout') }}</a>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>

{!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}
