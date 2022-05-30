@php
    $cart = cart()->getCart();
    $cartItemsCount = $cart ? $cart->items->count() : trans('shop::app.minicart.zero');
@endphp

<mobile-header
    is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
    heading="{{ __('velocity::app.menu-navbar.text-category') }}"
    :header-content="{{ json_encode(app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents()) }}"
    category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
    cart-items-count="{{ $cartItemsCount }}"
    cart-route="{{ route('shop.checkout.cart.index') }}"
    :locale="{{ json_encode(core()->getCurrentLocale()) }}"
    :all-locales="{{ json_encode(core()->getCurrentChannel()->locales) }}"
    :currency="{{ json_encode(core()->getCurrentCurrency()) }}"
    :all-currencies="{{ json_encode(core()->getCurrentChannel()->currencies) }}"
>

    {{-- this is default content if js is not loaded --}}
    <div class="row">
        <div class="col-3">
            <div class="w-100 d-flex align-items-center">
                <div class="hamburger-wrapper pl-2">
                    <i class="fa fa- fa-bars"></i>
                </div>
                @guest('customer')
                <div class="login-wrapper pr-2">
                    <a class="unset" href="{{ route('customer.auth.create') }}">
                        <i class="fa fa-sign-in-alt"></i>
                    </a>

                </div>
                @endguest
            </div>
        </div>
        <div class="col-6">
            <a href="{{ route('shop.home.index') }}" aria-label="Logo" class="logo">
                <img src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt=""/>
            </a>
        </div>
        <div class="right-vc-header d-flex align-items-center justify-content-end col-3">
            <a class="unset cursor-pointer px-2">
                <i class="fa fa-search"></i>
            </a>
            <a href="{{ route('shop.checkout.cart.index') }}" class="unset px-2">
                <div class="icons-shop">
                    <i class="fa fa-shopping-cart"></i>
                </div>

                <div class="badge-wrapper">
                    <span class="badge" v-text="{{$cartItemsCount}}"></span>
                </div>
            </a>
        </div>
    </div>

    <template v-slot:greetings>

        @auth('customer')
            <a class="unset" href="{{ route('customer.profile.index') }}">
                {{ __('velocity::app.responsive.header.greeting', ['customer' => auth()->guard('customer')->user()->first_name]) }}
            </a>
        @endauth
    </template>
    @guest('customer')
    <template v-slot:login-link>
        <a class="unset d-flex" href="{{ route('customer.auth.create') }}">
            <i class="fa fa-sign-in-alt"></i>
        </a>
    </template>
    @endguest
    <template v-slot:customer-navigation>
        @auth('customer')
            <ul type="none" class="vc-customer-options">
                <li>
                    <a href="{{ route('customer.profile.index') }}" class="unset">
                        <i class="icon profile text-down-3"></i>
                        <span>{{ __('shop::app.header.profile') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('customer.reviews.index') }}" class="unset">
                        <i class="icon reviews text-down-3"></i>
                        <span>{{ __('velocity::app.shop.general.reviews') }}</span>
                    </a>
                </li>

                @if (core()->getConfigData('general.content.shop.wishlist_option'))
                    <li>
                        <a href="{{ route('customer.wishlist.index') }}" class="unset">
                            <i class="icon wishlist text-down-3"></i>
                            <span>{{ __('shop::app.header.wishlist') }}</span>
                        </a>
                    </li>
                @endif

                @if (core()->getConfigData('general.content.shop.compare_option'))
                    <li>
                        <a href="{{ route('velocity.customer.product.compare') }}" class="unset">
                            <i class="icon compare text-down-3"></i>
                            <span>{{ __('shop::app.customer.compare.text') }}</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('customer.orders.index') }}" class="unset">
                        <i class="icon orders text-down-3"></i>
                        <span>{{ __('velocity::app.shop.general.orders') }}</span>
                    </a>
                </li>

            </ul>
        @endauth
    </template>

    <template v-slot:extraNavigation>
        @auth('customer')
        <li>

                <form id="customerLogout" action="{{ route('customer.session.destroy') }}" method="POST">
                    @csrf

                    @method('DELETE')
                </form>

                <a
                    class="unset"
                    href="{{ route('customer.session.destroy') }}"
                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                    {{ __('shop::app.header.logout') }}
                </a>


        </li>
        @endauth
    </template>

    <template v-slot:logo>
        <a class="logo" href="{{ route('shop.home.index') }}" aria-label="Logo">
            <img src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt=""/>
        </a>
    </template>


    <template v-slot:search-bar>
        <div class="w-100">
            @include('velocity::shop.layouts.particals.search-bar')
        </div>
    </template>

</mobile-header>