@php
    if ($velocityMetaData){
        $advertisement =collect(json_decode($velocityMetaData->advertisement, true))->flatten()->first();
        $advertisement_path =null;
        if ($advertisement)
        $advertisement_path = asset('/storage/' .$advertisement);
        }
    $channel = core()->getCurrentChannel();

    $homeSEO = $channel->home_seo;
    $metaTitle = $channel->name;
    if (isset($homeSEO)) {
        $homeSEO = json_decode($channel->home_seo);
        $metaTitle = $homeSEO->meta_title;
        }
@endphp
@if($advertisement_path)
    <div class="w-100 top-bar-gif">
            <img class="w-100"
                 src="{{ $advertisement_path}}">
    </div>
@endif
<nav class="row align-items-stretch justify-content-around" id="top">
    <div class="col-sm-6">
        <a class="navbar-brand" href="{{ route('shop.home.index') }}" aria-label="Logo">
            <img class="logo" src="{{ $channel->logo_url ?? asset('images/logo-text.png') }}"
                 alt="{{$metaTitle}}"/>
        </a>
    </div>

    <div class="col-sm-6">
        <div class="align-items-center d-flex h-100 justify-content-end w-100">
            <img class="logo" src="{{ $channel->logo_url ?? asset('images/moto.png') }}?v=1.1"
                 alt="{{$channel->name}}"/>
        </div>
    </div>
</nav>