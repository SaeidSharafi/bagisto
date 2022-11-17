<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    {{-- title --}}
    <title>جهاد دانشگاهی استان قزوین - آموزش مجازی</title>

    {{-- meta data --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VDGXL6JN2L"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-VDGXL6JN2L');
    </script>
    {!! view_render_event('bagisto.shop.layout.head') !!}

    {{-- for extra head data --}}
    @yield('head')

    {{-- seo meta data --}}
    @yield('seo')

    {{-- fav icon --}}
    @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}"/>
    @else
        <link rel="icon" sizes="16x16" href="{{ asset('/themes/velocity/assets/images/static/v-icon.png') }}"/>
    @endif

    {{-- all styles --}}
    @include('shop::layouts.styles')
    <style>
        #player {
            width: 100vw;
            height: 99vh;
            display: flex;
            align-items: stretch;
            justify-content: stretch;
        }

        #player iframe {
            width: 100vw;
        }

        .wrap {
            width: 100%;
            background: #eee;
        }

        .wrap img {
            width: 100%;
            max-height: 80px;
            padding-bottom: 5px;
            border-radius: 15px;
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
    </style>
</head>
<body style="margin:0;overflow: hidden;">
<div class="py-2 wrap">
    <div class="container">
        <div class="align-items-center row">
            <div class="col-md-10 col-12">
                <div class="grid d-flex align-items-center justify-content-center" id="download-apps">
                    <a class="px-4 text-center" href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.exe" target="_blank">
                        <img src="https://spotplayer.ir/assets/img/platform/win.png">
                        <h6>نسخه Windows</h6>
                    </a>
                    <a class="px-4 text-center" href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.dmg" target="_blank">
                        <img src="https://spotplayer.ir/assets/img/platform/mac.png">
                        <h6>نسخه MacOS</h6>
                    </a>

                    <a class="px-4 text-center" href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.apk" target="_blank">
                        <img src="https://spotplayer.ir/assets/img/platform/android.png">
                        <h6>نسخه Android</h6>
                    </a>
                    <a class="px-4 text-center" href="https://app.spotplayer.ir/" target="_blank">
                        <img src="https://spotplayer.ir/assets/img/platform/web.png">
                        <h6>نسخه Web</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-2 col-12">
                <a target="_blank" href="https://app.spotplayer.ir{{$spotLicense->url}}" class="btn btn-block btn-lg btn-primary">دانلود دوره</a>
            </div>
        </div>
    </div>

</div>
<div id="player">

</div>
<script src="https://app.spotplayer.ir/assets/js/app-api.js"></script>

<script type="application/javascript">
    async function Play() {
        try {
            const sp = new SpotPlayer(document.getElementById('player'), '/');
            await sp.Open('{{$spotLicense->key}}');
        } catch (ex) {
            console.log("TEST");
            // console.log(ex);
        }
    }

    Play();
</script>

</body>

