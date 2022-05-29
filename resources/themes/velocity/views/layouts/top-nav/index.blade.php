@php
if ($velocityMetaData){
    $advertisement =optional(collect(json_decode($velocityMetaData->advertisement, true))->first())[1];
    if ($advertisement)
    $advertisement_path = asset('/storage/' .$advertisement);
    }
@endphp
<div class="w-100 d-none d-md-block top-bar-gif">
        <div style="height: 60px;">
            <img class="w-100"
                 src="{{ $advertisement_path ??  asset('images/top.gif')}}"
                 height="60" style="object-fit: cover;">
        </div>
</div>
<nav class="row align-items-stretch justify-content-around" id="top">
    <div class="col-sm-6">
        <a class="navbar-brand" href="{{ route('shop.home.index') }}" aria-label="Logo">
            <img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('images/logo-text.png') }}" alt="" />
        </a>
    </div>

    <div class="col-sm-6">
        <div class="align-items-center d-flex h-100 justify-content-end w-100">
            <img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('images/moto.png') }}" alt="" />
        </div>
    </div>
</nav>