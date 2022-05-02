@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')

@php
    $reviews = $reviewHelper->getReviews($product)->paginate(3);

    if (! isset($total)) {
        $total = $reviewHelper->getTotalReviews($product);
        $avgRatings = $reviewHelper->getAverageRating($product);
        $avgStarRating = round($avgRatings);
    }

    $percentageRatings = $reviewHelper->getPercentageRating($product);
@endphp

{!! view_render_event('bagisto.shop.products.review.before', ['product' => $product]) !!}

    @if ($total)
        @if (isset($accordian) && $accordian)
            <accordian :active="true">
                {{-- customer ratings --}}
                <div slot="header" class="col-lg-12 no-padding">
                    <h3 class="display-inbl">
                        {{ __('velocity::app.products.customer-rating') }}
                    </h3>

                    <i class="rango-arrow"></i>
                </div>

                <div class="row customer-rating" slot="body">
                    <div class="row full-width text-center mb30">
                        <div class="col-lg-12 col-xl-6">
                            <h4 class="col-lg-12 fs16">{{ $avgRatings }} {{ __('shop::app.reviews.star') }}</h4>

                            <star-ratings
                                :size="24"
                                :ratings="{{ $avgStarRating }}"
                            ></star-ratings>

                            <span class="fs16 fw6 display-block">
                                {{ __('shop::app.reviews.ratingreviews', [
                                    'rating' => $avgRatings,
                                    'review' => $total])
                                }}
                            </span>

                            @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
                                <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                                    <button type="button" class="theme-btn light">{{ __('velocity::app.products.write-your-review') }}</button>
                                </a>
                            @endif
                        </div>

                        <div class="col-lg-12 col-xl-6">

                            @for ($i = 5; $i >= 1; $i--)

                                <div class="row">
                                    <span class="col-3 no-padding fs16 fw6">{{ $i }} {{ __('shop::app.reviews.star') }}</span>

                                    <div class="col-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                                        <div style="width: {{ $percentageRatings[$i] }}%"></div>
                                    </div>

                                    <span class="col-2 no-padding fs16">{{ $percentageRatings[$i] }} %</span>
                                </div>
                            @endfor

                        </div>
                    </div>
                </div>
            </accordian>
        @else
            <div class="row customer-rating">
                <div class="row full-width text-center mb30">
                    <div class="col-lg-12 col-xl-6">
                        <h3 class="col-lg-12">{{ $avgRatings }} {{ __('shop::app.reviews.star') }}</h3>

                        <star-ratings
                            :size="24"
                            :ratings="{{ $avgStarRating }}"
                        ></star-ratings>

                        <span class="fs16 display-block">
                            {{ __('shop::app.reviews.ratingreviews', [
                                'rating' => $avgRatings,
                                'review' => $total])
                            }}
                        </span>

                        @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
                            <a href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                                <button type="button" class="theme-btn light">{{ __('velocity::app.products.write-your-review') }}</button>
                            </a>
                        @endif
                    </div>

                    <div class="col-lg-12 col-xl-6">

                        @for ($i = 5; $i >= 1; $i--)

                            <div class="row">
                                <span class="col-3 no-padding fs16 fw6">{{ $i }} ستاره</span>

                                <div class="col-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                                    <div style="width: {{ $percentageRatings[$i] }}%"></div>
                                </div>

                                <span class="col-2 no-padding fs16">{{ $percentageRatings[$i] }} %</span>
                            </div>
                        @endfor

                    </div>
                </div>
            </div>
        @endif

        @if (isset($accordian) && $accordian)
            <accordian :title="'{{ __('shop::app.products.total-reviews') }}'" :active="true">
                {{-- customer reviews --}}
                <div slot="header" class="col-lg-12 no-padding">
                    <h3 class="display-inbl">
                        {{ __('velocity::app.products.reviews-title') }}
                    </h3>

                    <i class="rango-arrow"></i>
                </div>

                <div class="customer-reviews" slot="body">
                    @foreach ($reviews as $review)
                        <div class="row">
                            <h4 class="col-lg-12 fs18">{{ $review->title }}</h4>

                            <star-ratings
                                :ratings="{{ $review->rating }}"
                                push-class="mr10 fs16 col-lg-12"
                            ></star-ratings>

                            <div class="review-description col-lg-12">
                                <span>{{ $review->comment }}</span>
                            </div>

                            <div class="col-lg-12 mt5">
                                <span>{{ __('velocity::app.products.review-by') }} -</span>

                                <span class="fs16 fw6">
                                    {{ $review->name }},
                                </span>

                                <span>{{ core()->formatDate($review->created_at, 'F d, Y') }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <a
                        href="{{ route('shop.reviews.index', ['slug' => $product->url_key ]) }}"
                        class="mb20 link-color"
                    >{{ __('velocity::app.products.view-all-reviews') }}</a>
                </div>
            </accordian>
        @else
            <h3 class="display-inbl mb20 col-lg-12 no-padding">
                {{ __('velocity::app.products.reviews-title') }}
            </h3>

            <div class="customer-reviews">
                @foreach ($reviews as $review)
                    <div class="row">
                        <h4 class="col-lg-12 fs18">{{ $review->title }}</h4>

                        <star-ratings
                            :ratings="{{ $review->rating }}"
                            push-class="mr10 fs16 col-lg-12"
                        ></star-ratings>

                        <div class="review-description col-lg-12">
                            <span>{{ $review->comment }}</span>
                        </div>

                        <div class="image col-lg-12">
                            @if (count($review->images) > 0)
                                @foreach ($review->images as $image)
                                    <img class="image" src="{{ $image->url }}" style="height: 50px; width: 50px; margin: 5px;">
                                @endforeach
                            @endif
                        </div>

                        <div class="col-lg-12 mt5">
                            @if ("{{ $review->name }}")
                                <span>{{ __('velocity::app.products.review-by') }} -</span>

                                <label>
                                    {{ $review->name }},
                                </label>
                            @endif

                            <span>{{ core()->formatDate($review->created_at, 'F d, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
                    <a
                        href="{{ route('shop.reviews.index', ['slug' => $product->url_key ]) }}"
                        class="mb20 link-color"
                    >{{ __('velocity::app.products.view-all-reviews') }}</a>
            </div>
        @endif

    @else
        @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
            <div class="customer-rating" style="border: none">
                <div class="row customer-rating col-12 remove-padding-margin">
                    <h2 class="full-width">
                        {{ __('shop::app.reviews.write-review') }}
                    </h2>

                    <form
                        method="POST"
                        class="review-form"
                        @submit.prevent="onSubmit"
                        action="{{ route('shop.reviews.store', $product->product_id ) }}"
                        enctype="multipart/form-data">

                        @csrf

                        <div :class="`${errors.has('rating') ? 'has-error' : ''}`">
                            <label for="title" class="required">
                                {{ __('admin::app.customers.reviews.rating') }}
                            </label>
                            <star-ratings ratings="5" size="24" editable="true"></star-ratings>
                            <span :class="`control-error ${errors.has('rating') ? '' : 'hide'}`" v-if="errors.has('rating')" v-text="errors.first('rating')"></span>
                        </div>

                        <div :class="`${errors.has('title') ? 'has-error' : ''}`">
                            <label for="title" class="required">
                                {{ __('shop::app.reviews.title') }}
                            </label>
                            <input
                                type="text"
                                name="title"
                                class="control"
                                v-validate="'required'"
                                value="{{ old('title') }}" />

                            <span :class="`control-error ${errors.has('title') ? '' : 'hide'}`" v-text="errors.first('title')"></span>
                        </div>

                        @if (core()->getConfigData('catalog.products.review.guest_review') && ! auth()->guard('customer')->user())
                            <div :class="`${errors.has('name') ? 'has-error' : ''}`">
                                <label for="title" class="required">
                                    {{ __('shop::app.reviews.name') }}
                                </label>
                                <input  type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') }}">
                                <span :class="`control-error ${errors.has('name') ? '' : 'hide'}`" v-text="errors.first('name')"></span>
                            </div>
                        @endif

                        <div :class="`${errors.has('comment') ? 'has-error' : ''}`">
                            <label for="comment" class="required">
                                {{ __('admin::app.customers.reviews.comment') }}
                            </label>
                            <textarea
                                type="text"
                                class="control"
                                name="comment"
                                v-validate="'required'"
                                value="{{ old('comment') }}">
                            </textarea>
                            <span :class="`control-error ${errors.has('comment') ? '' : 'hide'}`" v-text="errors.first('comment')"></span>
                        </div>

                        <div class="submit-btn">
                            <button
                                type="submit"
                                class="theme-btn fs16">
                                {{ __('velocity::app.products.submit-review') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif

{!! view_render_event('bagisto.shop.products.review.after', ['product' => $product]) !!}