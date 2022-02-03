<header class="sticky-header border-bottom">


    <div class="right searchbar">
            <div class="row m-0">
                <div class="col-lg-7 col-md-12">
                    <div class="main-nav">
                        <ul type="none" class="no-margin">
                            <li class="top-level"><a href="/" target="_self">صفحه اصلی</a> </li>
                                <li class="top-level"><a href="{{route("customer.auth.create")}}" target="_self">لیست دوره‌ها</a></li>
                                <li class="top-level"><a href="{{route("customer.auth.create")}}" target="_self">درباره ما</a></li>
                                <li class="top-level"><a href="{{route("customer.auth.create")}}" target="_self">گواهی نامه</a></li>
                                <li class="top-level login"><a href="{{route("customer.auth.create")}}" target="_self">ورود</a></li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    @include('velocity::layouts.particals.search-bar')
                </div>

                <div class="col-lg-1 col-md-1 vc-full-screen">
                    <div class="left-wrapper">

                        {!! view_render_event('bagisto.shop.layout.header.wishlist.before') !!}

                            @include('velocity::layouts.particals.wishlist', ['isText' => false])

                        {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!}

                        {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}

                            @include('velocity::layouts.particals.compare', ['isText' => false])

                        {!! view_render_event('bagisto.shop.layout.header.compare.after') !!}

                        {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}

                            @include('shop::checkout.cart.mini-cart', ['isText' => false])

                        {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
                    </div>
                </div>
            </div>
        </div>

</header>

@push('scripts')
    <script type="text/javascript">
        (() => {
            document.addEventListener('scroll', e => {
                scrollPosition = Math.round(window.scrollY);

                if (scrollPosition > 50) {
                    document.querySelector('header').classList.add('header-shadow');
                } else {
                    document.querySelector('header').classList.remove('header-shadow');
                }
            });
        })();
    </script>
@endpush
