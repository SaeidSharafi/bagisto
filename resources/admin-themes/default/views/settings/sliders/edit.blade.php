@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.sliders.edit-title') }}
@stop

@section('content')
    <div class="content">
        @php $locale = core()->getRequestedLocaleCode(); @endphp

        <form method="POST" action="{{ route('admin.sliders.update', $slider->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sliders.index') }}'"></i>

                        {{ __('admin::app.settings.sliders.edit-title') }}

                        @if ($slider->locale)
                            <span class="locale">[{{ $slider->locale }}]</span>
                        @endif

                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.sliders.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">

                    @csrf()

                    {!! view_render_event('bagisto.admin.settings.slider.edit.before') !!}

                    <div class="control-group" :class="[errors.has('locale[]') ? 'has-error' : '']">
                        <label for="locale">{{ __('admin::app.datagrid.locale') }}</label>

                        <select class="control" id="locale" name="locale[]" data-vv-as="&quot;{{ __('admin::app.datagrid.locale') }}&quot;" value="" v-validate="'required'" multiple>
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ $localeModel->code }}" {{ in_array($localeModel->code, explode(',', $slider->locale)) ? 'selected' : ''}}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>

                        <span class="control-error" v-if="errors.has('locale[]')">@{{ errors.first('locale[]') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                        <label for="title" class="required">{{ __('admin::app.settings.sliders.name') }}</label>
                        <input type="text" class="control" name="title" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.settings.sliders.name') }}&quot;" value="{{ $slider->title ?: old('title') }}">
                        <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                    </div>

                    <?php $channels = core()->getAllChannels() ?>
                    <div class="control-group" :class="[errors.has('channel_id') ? 'has-error' : '']">
                        <label for="channel_id">{{ __('admin::app.settings.sliders.channels') }}</label>
                        <select class="control" id="channel_id" name="channel_id" data-vv-as="&quot;{{ __('admin::app.settings.sliders.channels') }}&quot;" value="" v-validate="'required'">
                            @foreach ($channels as $channel)
                                <option value="{{ $channel->id }}" @if ($channel->id == $slider->channel_id) selected @endif>
                                    {{ __(core()->getChannelName($channel)) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="control-error" v-if="errors.has('channel_id')">@{{ errors.first('channel_id') }}</span>
                    </div>

                    <div class="control-group date">
                        <label for="expired_at">{{ __('admin::app.settings.sliders.expired-at') }}</label>
                        <date>
                            <input type="text" name="expired_at" class="control" value="{{ old('expired_at') ?: $slider->expired_at }}"/>
                        </date>
                    </div>

                    <div class="control-group">
                        <label for="sort_order">{{ __('admin::app.settings.sliders.sort-order') }}</label>
                        <input type="text" class="control" id="sort_order" name="sort_order" value="{{ $slider->sort_order ?? old('sort_order') }}"/>
                    </div>

                    <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                        <label class="required">{{ __('admin::app.catalog.categories.image') }}</label>

                        <image-wrapper :button-label="'{{ __('admin::app.settings.sliders.image') }}'" input-name="image" :multiple="false" :images='"{{ Storage::url($slider->path) }}"'></image-wrapper>

                        <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                            @foreach ($errors->get('image.*') as $key => $message)
                                @php echo str_replace($key, 'Image', $message[0]); @endphp
                            @endforeach
                        </span>
                    </div>
                    <div class="control-group">
                        <label for="status">{{ __('dmin::app.settings.sliders.show_content') }}</label>
                        <input type="hidden" id="show_content" name="show_content" value="0">

                        <label class="switch">
                            <input type="checkbox" id="show_content" name="show_content" value="{{ $slider->show_content }}" {{ $slider->show_content ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="control-group">
                        <label for="content">{{ __('admin::app.settings.sliders.description') }}</label>
                        <div class="panel-body">
                            <textarea class="control" name="description" rows="5">{{ $slider->description ? : old('description') }}</textarea>
                        </div>

                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                    </div>
                    <div class="control-group">
                        <label for="sort_order">{{ __('admin::app.settings.sliders.button') }}</label>
                        <input type="text" class="control" id="button" name="button" value="{{ $slider->button ?? old('button') }}"/>
                        <span class="control-error" v-if="errors.has('button')">@{{ errors.first('button') }}</span>
                    </div>
                    {!! view_render_event('bagisto.admin.settings.slider.edit.after', ['slider' => $slider]) !!}
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('admin::layouts.tinymce')

    <script>
        {{--$(document).ready(function () {--}}

        {{--    tinyMCEHelper.initTinyMCE({--}}
        {{--        selector: 'textarea#tiny',--}}
        {{--        height: 200,--}}
        {{--        width: "100%",--}}
        {{--        cleanup_on_startup: false,--}}
        {{--        trim_span_elements: false,--}}
        {{--        verify_html: false,--}}
        {{--        cleanup: false,--}}
        {{--        convert_urls: false,--}}
        {{--        valid_children: '+a[div]',--}}
        {{--        forced_root_block : "",--}}
        {{--        extended_valid_elements : '*[*]',--}}
        {{--        plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',--}}
        {{--        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code | table',--}}
        {{--        image_advtab: true,--}}
        {{--        templates: [--}}
        {{--            { title: 'Test template 1', content: 'Test 1' },--}}
        {{--            { title: 'Test template 2', content: 'Test 2' }--}}
        {{--        ],--}}
        {{--        uploadRoute: '{{ route('admin.tinymce.upload') }}',--}}
        {{--        csrfToken: '{{ csrf_token() }}',--}}

        {{--    });--}}
        {{--});--}}
    </script>
@endpush
