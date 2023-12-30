<div class="px-2 my-2 product-card-wrapper">
    <a target="_blank"
       href="{{$item['url']}}"
       title="{{$item['fullname']}}" class="unset">
        <div class="card grid-card product-card border" style="aspect-ratio: auto;">
            <div class="product-image-container">
                <img loading="lazy" alt="{{$item['fullname']}}"
                     src="{{$item['image']}}"
                     data-src="{{$item['image']}}"
                     class="card-img-top lzy_img"></div>
            <div class="card-body">
                <h2 class="product-name w-100 no-padding">
                    {{$item['fullname']}}
                </h2>
            </div>
        </div>
    </a>
</div>
