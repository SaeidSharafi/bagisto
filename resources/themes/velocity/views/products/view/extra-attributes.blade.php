{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}


@if ($customAttributeValues)
    <ul class="nav nav-tabs nav-fill" id="extra-attributes" role="tablist">
        <li class="nav-item active" role="presentation">
            <a class="nav-link" id="curriculums-tab" data-toggle="tab"
               href="#curriculums" role="tab" aria-controls="curriculums" aria-selected="true">سرفصل های دوره</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="prerequisites-tab" data-toggle="tab" href="#prerequisites" role="tab"
               aria-controls="prerequisites" aria-selected="false">پیش نیاز ها</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="sources-tab" data-toggle="tab" href="#sources" role="tab"
               aria-controls="sources" aria-selected="false">منابع آموزشی</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="future_job-tab" data-toggle="tab" href="#future_job" role="tab"
               aria-controls="future_job" aria-selected="false">آینده شغلی</a>
        </li>
        <li class="nav-item" role="portfolio">
            <a class="nav-link" id="portfolio-tab" data-toggle="tab" href="#portfolio" role="tab"
               aria-controls="portfolio" aria-selected="false">نمونه کار فراگیر</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab"
               aria-controls="reviews" aria-selected="false">نظرات</a>
        </li>
    </ul>

    <div class="tab-content" id="extra-attributesContent">
        @foreach ($customAttributeValues as $attribute)
            <div class="tab-pane {{$loop->first ? 'show active' : ''}}" id="{{$attribute['code']}}" role="tabpanel" aria-labelledby="{{$attribute['code']}}-tab">
                @if($attribute['code'] == 'portfolio')
                    @php
                        $images = json_decode($attribute['value'])
                    @endphp
                    <div class="row portfolio-gallery">
                        @foreach($images as $image)
                            <div class="col-md-3 col-6">
                                <img src="{{$image->url}}" class="w-100">
                            </div>
                        @endforeach
                    </div>
                @else
                    {!! nl2br($attribute['value'])!!}
                @endif


            </div>
        @endforeach
        <div class="tab-pane" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
            {{-- reviews count --}}
            @include ('shop::products.view.reviews', ['accordian' => true])
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}