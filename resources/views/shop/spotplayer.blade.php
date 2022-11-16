<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>@yield('page_title')</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}"/>
    @else
        <link rel="icon" sizes="16x16" href="{{ bagisto_asset('images/favicon.ico') }}"/>
    @endif
    @section('seo')
        @if (! request()->is('/'))
            <meta name="description" content="{{ core()->getCurrentChannel()->description }}"/>
        @endif
    @show
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
        .wrap{
            width: 100%;
            height: 100px;
        }
        .wrap img{
            width: 100%;
            max-height: 80px;
        }
        .grid{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h6 {
            margin: 0;
            font-family: "IranSans";
        }
    </style>
</head>
<body style="margin:0;overflow: hidden;">
<div class="wrap">
        <div class="grid" id="download-apps">
            <a href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.exe" target="_blank">
                <img src="https://spotplayer.ir/assets/img/platform/win.png">
                <h6>نسخه
                    Windows</h6>
            </a>
            <a href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.dmg" target="_blank">
                <img src="https://spotplayer.ir/assets/img/platform/mac.png">
                <h6>نسخه MacOS</h6>
            </a>

            <a href="https://app.spotplayer.ir/assets/bin/spotplayer/setup.apk" target="_blank">
                <img src="https://spotplayer.ir/assets/img/platform/android.png">
                <h6>نسخه Android</h6>
            </a>
            <a href="https://app.spotplayer.ir/" target="_blank">
                <img src="https://spotplayer.ir/assets/img/platform/web.png">
                <h6>نسخه Web</h6>
            </a>
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

