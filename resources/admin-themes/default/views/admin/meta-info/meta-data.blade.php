@extends('admin::layouts.content')

@section('page_title')
    {{ __('velocity::app.admin.meta-data.title') }}
@stop

@php
    $locale = core()->checkRequestedLocaleCodeInRequestedChannel();
    $channel = core()->getRequestedChannelCode();
    $channelLocales = core()->getAllLocalesByRequestedChannel()['locales'];
@endphp

@section('content')
    <div class="content">
        <form
            method="POST"
            @submit.prevent="onSubmit"
            enctype="multipart/form-data"
            @if ($metaData)
                action="{{ route('velocity.admin.store.meta-data', ['id' => $metaData->id]) }}"
            @else
                action="{{ route('velocity.admin.store.meta-data', ['id' => 'new']) }}"
            @endif
            >

            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('velocity::app.admin.meta-data.title') }}</h1>
                </div>

                <input type="hidden" name="locale" value="{{ $locale }}" />
                <input type="hidden" name="channel" value="{{ $channel }}" />

                <div class="control-group">
                    <select class="control" id="channel-switcher" name="channel">
                        @foreach (core()->getAllChannels() as $channelModel)

                            <option
                                value="{{ $channelModel->code }}" {{ ($channelModel->code) == $channel ? 'selected' : '' }}>
                                {{ core()->getChannelName($channelModel) }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="control-group">
                    <select class="control" id="locale-switcher" name="locale">
                        @foreach ($channelLocales as $localeModel)

                            <option
                                value="{{ $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                {{ $localeModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('velocity::app.admin.meta-data.update-meta-data') }}
                    </button>
                </div>
            </div>

            <accordian :title="'{{ __('velocity::app.admin.meta-data.general') }}'" :active="true">
                <div slot="body">
                    <div class="control-group slider-switch">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.activate-slider') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <label class="switch">
                            <input
                                id="slides"
                                name="slides"
                                type="checkbox"
                                class="control"
                                data-vv-as="&quot;slides&quot;"
                                {{ $metaData && $metaData->slider ? 'checked' : ''}} />

                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="control-group sidebar-categories">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.sidebar-categories') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <input
                            type="number"
                            min="0"
                            class="control"
                            id="sidebar_category_count"
                            name="sidebar_category_count"
                            value="{{ $metaData ? $metaData->sidebar_category_count : '10' }}" />
                    </div>

                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.map_iframe') }}
                        </label>
                        <textarea
                            style="direction: ltr;"
                            class="control"
                            id="map_iframe"
                            name="map_iframe">{{ $metaData ? $metaData->map_iframe : null }}</textarea>
                    </div>
                    <div class="control-group header-count" style="display: none;">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.header_content_count') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <input
                            type="number"
                            min="0"
                            class="control"
                            id="header_content_count"
                            name="header_content_count"
                            value="{{ $metaData ? $metaData->header_content_count : '5' }}" />
                    </div>




                    <div class="control-group home-page-content">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.home-page-content') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <textarea
                            class="control"
                            id="home_page_content"
                            name="home_page_content">
                            {{ $metaData ? $metaData->home_page_content : '' }}
                        </textarea>
                    </div>

                    <div class="control-group product-policy" style="display: none;">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.product-policy') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <textarea
                            class="control"
                            id="product-policy"
                            name="product_policy">
                            {{ $metaData ? $metaData->product_policy : '' }}
                        </textarea>
                    </div>

                </div>
            </accordian>
            <accordian :title="'{{ __('admin.meta-data.blog') }}'" :active="false">

                <div slot="body">
                    <div class="control-group blog-title">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.blog_title') }}
                        </label>

                        <input
                            type="text"
                            class="control"
                            id="blog_title"
                            name="blog_title"
                            value="{{ $metaData ? $metaData->blog_title : '' }}" />
                    </div>
                    <div class="control-group blog-url">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.blog_url') }}
                        </label>

                        <input
                            type="text"
                            class="control"
                            id="blog_url"
                            name="blog_url"
                            value="{{ $metaData ? $metaData->blog_url : '' }}" />
                    </div>
                    <div class="control-group">
                        <label>{{ __('admin.meta-data.blog_image') }}</label>
                        @if(isset($metaData->blog_image) && $metaData->blog_image)

                            <image-wrapper
                                :multiple="false"
                                input-name="blog_image"
                                :images='"{{ Storage::url($metaData->blog_image) }}"'
                                :button-label="'{{ __('velocity::app.admin.meta-data.add-image-btn-title') }}'">
                            </image-wrapper>
                        @else
                            <image-wrapper
                                :multiple="false"
                                input-name="blog_image"
                                :button-label="'{{ __('velocity::app.admin.meta-data.add-image-btn-title') }}'">
                            </image-wrapper>
                        @endif
                    </div>
                </div>
            </accordian>
            <accordian :title="'{{ __('admin.meta-data.special') }}'" :active="false">

                <div slot="body">
                    <div class="control-group special-id">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.special_id') }}
                        </label>

                        <input
                            type="text"
                            class="control"
                            id="special_id"
                            name="special_id"
                            value="{{ $metaData ? $metaData->special_id : '' }}"/>
                    </div>
                    <div class="control">
                        <p-date-picker
                            name="special_from"
                            id="special_from"
                            clearable
                            initial-value="{{  old($metaData->special_fom) ?: $metaData->special_fom}}"
                            placeholder="از تاریخ"></p-date-picker>
                    </div>
                    <div class="control">
                        <p-date-picker
                            name="special_to"
                            id="special_to"
                            clearable
                            initial-value="{{  old($metaData->special_to) ?: $metaData->special_to}}"
                            placeholder="تا تاریخ"></p-date-picker>
                    </div>
                    <div class="control-group">
                        <label>{{ __('admin.meta-data.special_image') }}</label>
                        @if(isset($metaData->special_image) && $metaData->special_image)

                            <image-wrapper
                                :multiple="false"
                                input-name="special_image"
                                :images='"{{ Storage::url($metaData->special_image) }}"'
                                :button-label="'{{ __('velocity::app.admin.meta-data.add-image-btn-title') }}'">
                            </image-wrapper>
                        @else
                            <image-wrapper
                                :multiple="false"
                                input-name="special_image"
                                :button-label="'{{ __('velocity::app.admin.meta-data.add-image-btn-title') }}'">
                            </image-wrapper>
                        @endif
                    </div>
                </div>
            </accordian>
            <accordian :title="'{{ __('velocity::app.admin.meta-data.images') }}'" :active="false">
                @php
                    $images = [
                        'top' => [],
                    ];

                    $index = 0;

                    foreach ($metaData->get('locale')->all() as $key => $value) {
                        if ($value->locale == $locale) {
                            $index = $key;
                        }
                    }

                    $advertisement = json_decode($metaData->get('advertisement')->all()[$index]->advertisement, true);
                @endphp
                <div slot="body">
                    <div class="control-group">
                        <label>{{ __('app.velocity.admin.meta-data.advertisement-top') }}</label>

                        @if(! isset($advertisement['top']) || ! count($advertisement['top']))
                            @php
                                $images['top'][] = [
                                    'id' => 'image_1',
                                    'url' => asset('/themes/velocity/assets/images/big-sale-banner.webp'),
                                ];
                            @endphp

                            <image-wrapper
                                :multiple="true"
                                input-name="images[top]"
                                :images='@json($images['top'])'
                                :button-label="'{{ __('velocity::app.admin.meta-data.add-image-btn-title') }}'">
                            </image-wrapper>
                        @else
                            @foreach ($advertisement['top'] as $index => $image)
                                @php
                                    $images['top'][] = [
                                        'id' => 'image_' . $index,
                                        'url' => asset('/storage/' . $image),
                                    ];
                                @endphp
                            @endforeach

                            <image-wrapper
                                :multiple="true"
                                input-name="images[top]"
                                :images='@json($images['top'])'
                                :button-label="'{{ __('velocity::app.admin.meta-data.add-image-btn-title') }}'">
                            </image-wrapper>
                        @endif
                    </div>
                </div>
            </accordian>

            <accordian :title="'{{ __('velocity::app.admin.meta-data.footer') }}'" class="footer" :active="false">
                <div slot="body">

                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.telegram_url') }}
                        </label>
                        <input
                            class="control"
                            id="telegram_url"
                            name="telegram_url"
                        value=" {{ $metaData ? $metaData->telegram_url : null }}"/>
                    </div>
                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.instagram_url') }}
                        </label>
                        <input
                            class="control"
                            id="instagram_url"
                            name="instagram_url"
                            value=" {{ $metaData ? $metaData->instagram_url : null }}"/>
                    </div>
                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.aparat_url') }}
                        </label>
                        <input
                            class="control"
                            id="aparat_url"
                            name="aparat_url"
                            value=" {{ $metaData ? $metaData->aparat_url : null }}"/>
                    </div>
                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('admin.meta-data.bale_url') }}
                        </label>
                        <input
                            class="control"
                            id="bale_url"
                            name="bale_url"
                            value=" {{ $metaData ? $metaData->bale_url : null }}"/>
                    </div>
                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.subscription-content') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <textarea
                            class="control"
                            id="subscription_bar_content"
                            name="subscription_bar_content">
                            {{ $metaData ? $metaData->subscription_bar_content : '' }}
                        </textarea>
                    </div>

                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.footer-left-content') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <textarea
                            class="control"
                            id="footer_left_content"
                            name="footer_left_content">
                            {{ $metaData ? $metaData->footer_left_content : '' }}
                        </textarea>
                    </div>

                    <div class="control-group">
                        <label style="width:100%;">
                            {{ __('velocity::app.admin.meta-data.footer-middle-content') }}
                            <span class="locale">[{{ $channel }} - {{ $locale }}]</span>
                        </label>

                        <textarea
                            class="control"
                            id="footer_middle_content"
                            name="footer_middle_content">
                            {{ $metaData ? $metaData->footer_middle_content : '' }}
                        </textarea>
                    </div>
                </div>
            </accordian>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            tinymce.init({
                height: 200,
                width: "100%",
                image_advtab: true,
                cleanup_on_startup: false,
                trim_span_elements: false,
                verify_html: false,
                cleanup: false,
                convert_urls: false,
                valid_children: '+a[div]',
                forced_root_block : "",
                extended_valid_elements : '*[*]',
                selector: 'textarea#home_page_content,textarea#footer_left_content,textarea#subscription_bar_content,textarea#footer_middle_content,textarea#product-policy',
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
            });

            $('#channel-switcher, #locale-switcher').on('change', function (e) {
                $('#channel-switcher').val()

                if (event.target.id == 'channel-switcher') {
                    let locale = "{{ $channelLocales->first()->code }}";

                    $('#locale-switcher').val(locale);
                }

                var query = '?channel=' + $('#channel-switcher').val() + '&locale=' + $('#locale-switcher').val();

                window.location.href = "{{ route('velocity.admin.meta-data')  }}" + query;
            })
        });
    </script>
@endpush
