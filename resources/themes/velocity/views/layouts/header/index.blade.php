<header class="sticky-header border-bottom">


    <div class="right searchbar">
        <div class="row m-0">
            <div class="col-lg-7 col-md-12">
                <div class="main-nav">
                    <ul type="none" class="no-margin">
                        <li class="top-level">
                            <sidebar-header heading="{{ __('shop.header.categories') }}">

                                {{-- this is default content if js is not loaded --}}
                                <div class="main-category fs16 unselectable fw6 left">
                                    <i class="rango-view-list text-down-4 align-vertical-top fs18"></i>

                                    <span class="pr5">{{ __('shop.header.categories') }}</span>
                                </div>

                            </sidebar-header>
                        </li>

                        <li class="top-level"><a href="{{route("shop.aboutus")}}" target="_self">درباره ما</a></li>
                        <li class="top-level"><a href="{{route("shop.contactus")}}" target="_self">تماس با ما</a></li>
                        <li class="top-level"><a href="{{config('app.blog_url')}}" target="_blank">بلاگ</a></li>
                        @guest('customer')
                        <li class="top-level login"><a href="{{route("customer.auth.create")}}" target="_self">ورود</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-1 vc-full-screen">
                <div class="left-wrapper d-flex">
                    @include('velocity::layouts.top-nav.login-section')

                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                @include('velocity::layouts.particals.search-bar')
            </div>

        </div>
    </div>
    <div class="w-100 remove-padding-margin">
        <sidebar-component
            main-sidebar=true
            id="sidebar-level-0"
            url="{{ url()->to('/') }}"
            category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
            add-class="category-list-container pt10">
        </sidebar-component>
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
