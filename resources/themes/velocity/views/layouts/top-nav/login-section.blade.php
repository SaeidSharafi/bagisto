{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}

<div id="account">
    @auth('customer')
    <div
        class="d-inline-block welcome-content dropdown-toggle account-menu d-flex align-items-center"
    >
        <div class="welcome-content">
            <i class="fa-user fa px-1"></i>
            <i class="fa fa-caret-down"></i>
        </div>
    </div>
    <div class="dropdown-list">
        <div class="dropdown-label">
            {{ auth()->guard('customer')->user()->first_name }} {{ auth()->guard('customer')->user()->last_name }}
        </div>

        <div class="dropdown-container">
            <ul type="none">
                <li>
                    <a
                        href="{{ route('customer.profile.index') }}"
                        class="unset"
                        >{{ __('shop::app.header.profile') }}</a
                    >
                </li>

                <li>
                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="unset"
                        >{{ __('velocity::app.shop.general.orders') }}</a
                    >
                </li>
                <li>
                    <a
                        href="{{ route('customer.my-course.index') }}"
                        class="unset"
                        >{{
                            __('app.customer.account.moodle.index.page-title')
                        }}</a
                    >
                </li>
                @php $showCompare =
                core()->getConfigData('general.content.shop.compare_option') ==
                "1" ? true : false; $showWishlist =
                core()->getConfigData('general.content.shop.wishlist_option') ==
                "1" ? true : false; @endphp @if ($showWishlist)
                <li>
                    <a
                        href="{{ route('customer.wishlist.index') }}"
                        class="unset"
                        >{{ __('shop::app.header.wishlist') }}</a
                    >
                </li>
                @endif @if ($showCompare)
                <li>
                    <a
                        href="{{ route('velocity.customer.product.compare') }}"
                        class="unset"
                        >{{ __('velocity::app.customer.compare.text') }}</a
                    >
                </li>
                @endif

                <li>
                    <form
                        id="customerLogout"
                        action="{{ route('customer.session.destroy') }}"
                        method="POST"
                    >
                        @csrf @method('DELETE')
                    </form>

                    <a
                        class="unset"
                        href="{{ route('customer.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                    >
                        {{ __('shop::app.header.logout') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
    @endauth
</div>

{!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}
