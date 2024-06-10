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

                        <div class="col-lg-12 mt5 review-meta">
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
        <h4 class="display-inbl mb20 col-lg-12 no-padding section-title">
            {{ __('velocity::app.products.reviews-title') }}
        </h4>

            <div class="customer-reviews">
                @foreach ($reviews as $review)
                    <div class="row d-flex pb-0 pt-2 border-bottom">
                        <div class="col-7 review-title">
                            <h4 class="w-100">{{ $review->title }}</h4>
                        </div>
                        <div class="col-5">
                            <star-ratings
                                :ratings="{{ $review->rating }}"
                                push-class="w-100"
                            ></star-ratings>
                        </div>

                        <div class="review-description col-lg-12">
                            <p class="m-0">{{ $review->comment }}</p>
                        </div>

                        <div class="image col-lg-12">
                            @if (count($review->images) > 0)
                                @foreach ($review->images as $image)
                                    <img class="image" src="{{ $image->url }}" style="height: 50px; width: 50px; margin: 5px;">
                                @endforeach
                            @endif
                        </div>

                        <div class="col-lg-12 mt5  review-meta">
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

            </div>
        <div class="d-flex border-top border-dark mt-3 pt-3 view-all">
            <a
                href="{{ route('shop.reviews.index', ['slug' => $product->url_key ]) }}"
                class="btn btn-outline-dark mx-2"
            >{{ __('velocity::app.products.view-all-reviews') }}</a>
{{--            @if ($hasOrder)--}}
                <button type="button" class="btn btn-md btn-primary" @click="showModal('addReview')">
                    {{ __('shop::app.products.write-review-btn') }}
                </button>
{{--            @endif--}}
        </div>
    @endif
@else
{{--    @if ($hasOrder)--}}
        <button type="button" class="btn btn-md btn-primary" @click="showModal('addReview')">
            {{ __('shop::app.products.write-review-btn') }}
        </button>
{{--    @endif--}}
@endif

@if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())

        <modal id="addReview" :is-open="modalIds.addReview">

            <h3 slot="header">{{ __('shop::app.products.write-review-btn') }}</h3>

            <div slot="body">
                <div class="customer-rating" style="border: none">
                    <div class="row customer-rating col-12 remove-padding-margin">
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
                                    data-vv-as="{{ __('shop::app.reviews.title') }}"
                                    value="{{ old('title') }}"/>

                                <span :class="`control-error ${errors.has('title') ? '' : 'hide'}`" v-text="errors.first('title')"></span>
                            </div>

                            @if (core()->getConfigData('catalog.products.review.guest_review') && ! auth()->guard('customer')->user())
                                <div :class="`${errors.has('name') ? 'has-error' : ''}`">
                                    <label for="title" class="required">
                                        {{ __('shop::app.reviews.name') }}
                                    </label>
                                    <input type="text" class="control" name="name"
                                           v-validate="'required'" value="{{ old('name') }}"
                                           data-vv-as="{{ __('shop::app.reviews.name') }}"
                                    >
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
                                    data-vv-as="{{ __('admin::app.customers.reviews.comment') }}"
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
            </div>
        </modal>
@endif

{!! view_render_event('bagisto.shop.products.review.after', ['product' => $product]) !!}