<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <script
            async
            src="https://www.googletagmanager.com/gtag/js?id=G-KFQ1CRSG4Y"
        ></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'G-KFQ1CRSG4Y');
        </script>

        {{-- title --}}
        <title>جهاد دانشگاهی استان قزوین - آموزش مجازی</title>

        {{-- meta data --}}
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="base-url" content="{{ url()->to('/') }}" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Google tag (gtag.js) -->
        {!! view_render_event('bagisto.shop.layout.head') !!}

        {{-- for extra head data --}}
        @yield('head')

        {{-- seo meta data --}}
        @yield('seo')

        {{-- fav icon --}}
        @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
        @else
        <link
            rel="icon"
            sizes="16x16"
            href="{{
                asset('/themes/velocity/assets/images/static/v-icon.png')
            }}"
        />
        @endif

        {{-- all styles --}}
        @include('shop::layouts.styles')
        <style>
            html,body{
                font-size: 12px;
            }
            #download-apps img{
             border-radius: 100%;
             background-color: transparent;
             margin-bottom: 5px;
             max-width: 100px;
             width: 100%;
            }
             .grid a {
                 color: #000000;
             }

             .grid h6 {
                 font-family: Source Sans Pro, "IranSans", Helvetica Neue, Helvetica, Arial, sans-serif;
                 font-weight: bold;
                 font-size: 1.2rem;
             }

             }
             section{
                 width: 100%;
             }
             section.banner{
                 background: url('/images/shop/spot/top-bg.webp') scroll center no-repeat;
                 background-size: cover;
             }
        </style>
        <!-- <script src="https://cdn.tailwindcss.com"></script> -->
        <link rel="stylesheet" type="text/css" href="/spot.css">
    </head>
    <body style="margin: 0">
        <div class="">
            <header></header>
            <main class="text-xl">
                <section
                    class="w-full lg:pt-40 min-h-[30rem] banner flex flex-col items-center justify-center lg:px-52 px-10 text-white text-right relative pb-72 md:pb-96 z-10 py-20"
                >
                    <div class="max-w-[820px]">
                        <p class="text-2xl font-bold mb-3 text-right w-full">
                            فراگیر گرامی
                        </p>
                        <p class="w-full mb-6">
                            جهت مشاهده ویدئوی آموزشی دوره مورد نظر، ابتدا لایسنس
                            زیر را کپی کرده و در داخل نرم‌افزار اسپات‌پلیر قرار
                            دهید.
                        </p>
                        <p class="w-full mb-3 text-2xl font-bold text-cyan-400">
                            <span>نام دوره: </span>
                            <span>{{ $course_name }}</span>
                        </p>
                        <div class="flex items-center w-full relative">
                            <input
                                type="text"
                                id="spot-license"
                                readonly
                                value="{{ $spotLicense->key }}"
                                class="w-full rounded-r-3xl h-14 text-black"
                            />
                            <a
                                onclick="copy(event)"
                                href=""
                                class="cursor-pointer rounded-l-3xl shrink-0 px-2 bg-cyan-400 h-14 flex items-center justify-center"
                                >کپی لایسنس</a
                            >
                            <div
                                id="toast"
                                class="hidden absolute w-full justify-center"
                            >
                                <span
                                    class="inline-block px-4 py-2 rounded bg-black/70"
                                >
                                    کپی شد
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    class="download flex items-center flex-col relative w-full min-h-96 z-20 lg:px-52 px-10 pb-10"
                >
                    <div
                        class="-top-[15%] absolute h-32 overflow-hidden right-0 w-full"
                    >
                        <div
                            class="w-[110%] absolute rounded-t-[120%] right-0 left-0 bg-white h-32 -mx-[5%] z-30"
                        ></div>
                    </div>
                    <div
                        class="-translate-y-1/2 w-full object-contain relative -mt-24 z-40 h-full -mb-80 md:-mb-48"
                    >
                        <img
                            src="/images/shop/spot/pc.png"
                            class="w-full mx-auto object-contain h-[30rem]"
                            alt="pc-decoration"
                        />
                    </div>
                    <div class="w-ful flex justify-center">
                        <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                        <svg
                            width="32px"
                            height="32px"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M16.5303 8.96967C16.8232 9.26256 16.8232 9.73744 16.5303 10.0303L12.5303 14.0303C12.2374 14.3232 11.7626 14.3232 11.4697 14.0303L7.46967 10.0303C7.17678 9.73744 7.17678 9.26256 7.46967 8.96967C7.76256 8.67678 8.23744 8.67678 8.53033 8.96967L12 12.4393L15.4697 8.96967C15.7626 8.67678 16.2374 8.67678 16.5303 8.96967Z"
                                fill="#1536c5"
                            />
                        </svg>
                    </div>
                    <div
                        class="flex flex-col items-center w-full pt-10 text-[#243fb3]"
                    >
                        <div class="max-w-[820px] text-right">
                            <p class="font-bold text-2xl mb-3">
                                دریافت نرم‌افزار اسپات‌پلیر
                            </p>
                            <p>
                                لطفا بر اساس سیستم عاملی که برای مشاهده دوره قصد
                                استفاده از آن را دارید، نرم‌افزار اسپات‌پلیر را
                                دانلود نمایید.
                            </p>
                            <div
                                class="grid d-flex items-start justify-center bg-gray-300 rounded mt-10 py-4 gap-4"
                                id="download-apps"
                            >
                                <a
                                    class="px-4 text-center"
                                    href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.exe"
                                    target="_blank"
                                >
                                    <img
                                        src="https://spotplayer.ir/assets/img/platform/win.png"
                                    />
                                    <h6>نسخه Windows</h6>
                                </a>
                                <a
                                    class="px-4 text-center"
                                    href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.dmg"
                                    target="_blank"
                                >
                                    <img
                                        src="https://spotplayer.ir/assets/img/platform/mac.png"
                                    />
                                    <h6>نسخه MacOS</h6>
                                </a>

                                <a
                                    class="px-4 text-center"
                                    href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.apk"
                                    target="_blank"
                                >
                                    <img
                                        src="https://spotplayer.ir/assets/img/platform/android.png"
                                    />
                                    <h6>نسخه Android</h6>
                                </a>
                                <a
                                    class="px-4 text-center"
                                    href="https://app.spotplayer.ir/"
                                    target="_blank"
                                >
                                    <img
                                        src="https://spotplayer.ir/assets/img/platform/web.png"
                                    />
                                    <h6>نسخه Web</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="w-ful flex justify-center">
                    <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                    <svg
                        width="32px"
                        height="32px"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M16.5303 8.96967C16.8232 9.26256 16.8232 9.73744 16.5303 10.0303L12.5303 14.0303C12.2374 14.3232 11.7626 14.3232 11.4697 14.0303L7.46967 10.0303C7.17678 9.73744 7.17678 9.26256 7.46967 8.96967C7.76256 8.67678 8.23744 8.67678 8.53033 8.96967L12 12.4393L15.4697 8.96967C15.7626 8.67678 16.2374 8.67678 16.5303 8.96967Z"
                            fill="#1536c5"
                        />
                    </svg>
                </div>
                <section
                    class="py-10 w-full lg:px-52 px-10 justify-center flex"
                >
                    <div class="max-w-[820px] text-right">
                        <p class="font-bold text-2xl text-[#243fb3] mb-10">
                            راهنمای نصب
                        </p>
                        <img
                            src="/images/shop/spot/poster.jpg"
                            class="object-contain w-full"
                        />
                    </div>
                </section>
            </main>
        </div>
        <script>
            function copy(event) {
                event.preventDefault();

                // Get the text field
                var copyText = document.getElementById('spot-license');
                var toast = document.getElementById('toast');

                // Select the text field
                copyText.select();
                copyText.setSelectionRange(0, 99999); // For mobile devices

                // Copy the text inside the text field
                navigator.clipboard.writeText(copyText.value);
                toast.classList.add('flex');
                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('flex');
                }, 3000);
                // Alert the copied text
            }
        </script>
    </body>
</html>
