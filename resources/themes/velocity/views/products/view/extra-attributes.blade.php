{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}
@php
    $customAttributeValues = $customAttributeValues->filter(fn($attribute) => $attribute['value']);
    $attr = $customAttributeValues->pluck('code');
@endphp
@if ($customAttributeValues)
    <ul class="nav nav-tabs nav-fill d-none d-md-flex" id="extra-attributes" role="tablist">
        <li class="nav-item active" role="presentation">
            <a class="nav-link" id="curriculums-tab" data-toggle="tab"
               href="#curriculums" role="tab" aria-controls="curriculums" aria-selected="true">سرفصل های دوره</a>
        </li>
        @if ($attr->contains('prerequisites'))
            <li class="nav-item" role="prerequisites">
                <a class="nav-link" id="prerequisites-tab" data-toggle="tab" href="#prerequisites" role="tab"
                   aria-controls="prerequisites" aria-selected="false">پیش نیاز ها</a>
            </li>
        @endif
        @if ($attr->contains('sources'))
            <li class="nav-item" role="sources">
                <a class="nav-link" id="sources-tab" data-toggle="tab" href="#sources" role="tab"
                   aria-controls="sources" aria-selected="false">منابع آموزشی</a>
            </li>
        @endif
        @if ($attr->contains('future_job'))
            <li class="nav-item" role="future_job">
                <a class="nav-link" id="future_job-tab" data-toggle="tab" href="#future_job" role="tab"
                   aria-controls="future_job" aria-selected="false">آینده شغلی</a>
            </li>
        @endif
        @if ($attr->contains('portfolio'))
            <li class="nav-item" role="portfolio">
                <a class="nav-link" id="portfolio-tab" data-toggle="tab" href="#portfolio" role="tab"
                   aria-controls="portfolio" aria-selected="false">نمونه کار فراگیر</a>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab"
               aria-controls="reviews" aria-selected="false">نظرات</a>
        </li>
    </ul>

    <div class="tab-content" id="extra-attributesContent">
        @foreach ($customAttributeValues as $attribute)
            <div class="tab-pane {{$loop->first ? 'show active' : ''}}" id="{{$attribute['code']}}" role="tabpanel" aria-labelledby="{{$attribute['code']}}-tab">
                <h5 class="d-block d-md-none border-bottom border-dark pb-2 w-100">{{$attribute['label']}}</h5>
                <div class="contents pt-2 pt-md-0">
                    @if($attribute['code'] == 'portfolio')
                        @php
                            $images = json_decode($attribute['value'])
                        @endphp
                        <div class="row portfolio-gallery">
                            @if ($images)
                                @foreach($images as $image)
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{$image->url}}" target="_blank">
                                            <img src="{{$image->url}}" class="w-100">
                                        </a>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    @else
                        {!! nl2br($attribute['value'])!!}
                    @endif
                </div>

            </div>
        @endforeach
        <div class="tab-pane" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
            {{-- reviews count --}}
            <h5 class="d-block d-md-none border-bottom border-dark pb-2 w-100">نظرات</h5>
            <div class="contents pt-2 pt-md-0">
                @include ('shop::products.view.reviews', ['accordian' => false,'hasOrder' => $hasOrder])
            </div>
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}