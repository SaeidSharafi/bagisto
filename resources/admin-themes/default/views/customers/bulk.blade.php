@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.customers.customers.bulk-page-title') }}
@stop
@push('css')
    <style>
        .bg-fail {
            background-color: rgba(250, 162, 162, 0.3);
        }

        .bg-success {
            background-color: #c9ffb1;
        }

        .form-container {
            padding: 25px;
        }

        .control-error {
            padding: 10px 5px;
            display: block;
            color: red;
        }

        label {
            background-color: indigo;
            color: white;
            padding: 0.5rem;
            font-family: sans-serif;
            border-radius: 0.3rem;
            cursor: pointer;
            margin-top: 1rem;
        }

        #file-chosen {
            margin-left: 0.3rem;
            font-family: sans-serif;
        }
    </style>
@endpush
@section('content')

    <div class="content">
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.customers.bulk.upload') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.customer.index') }}'"></i>

                        {{ __('admin.customers.customers.bulk-page-title') }}

                        {{ Config::get('carrier.social.facebook.url') }}
                    </h1>
                </div>


                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        @if ($validated)
                            {{ __('admin.customers.customers.bulk-btn-title') }}

                        @else
                            {{ __('admin.customers.customers.bulk-check-btn-title') }}
                        @endif
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container p4">
                    @csrf()
                    <div class="form-control">
                        <!-- actual upload which is hidden -->
                        @if($file_name)
                            <input type="hidden" value="{{  $file_name}}" name="file_path">
                        @endif
                        <input name="uploaded_file" type="file" id="actual-btn"
                               accept=".xls,.xlsx,.csv"
                               onchange="setname()" hidden/>
                        <!-- name of file chosen -->
                        <!-- our custom upload button -->
                        <label for="actual-btn">انتخاب فایل</label>
                        <span id="file-chosen"></span>

                        @if ($errors->has('uploaded_file'))
                            <span class="control-error" v-text="'{{$errors->first('uploaded_file')}}'"></span>

                        @endif


                        <input type="hidden" value="{{$validated}}" name="validated">
                    </div>

                    <br>

                </div>
                @if ($data && $data->count())
                    <div class="table table-responsive">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>وضعیت</th>
                                @foreach($data->first()['values'] as $key => $value)
                                    <th class="grid_head">{{$key}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $index => $row)
                                <tr class="{{isset($row['errors']) && $row['errors'] ? 'bg-fail' : 'bg-succes'}}">
                                    <td>
                                        @if (isset($row['errors']) && $row['errors'] )
                                            <i class="icon canceled-icon"></i>
                                        @else
                                            <i class="icon completed-icon"></i>
                                        @endif
                                    </td>

                                    @foreach($row['values'] as $field => $value)
                                        <td>
                                            <span style="display: block">
                                             {{$value}}
                                            </span>
                                            @if (isset($row['errors']) && $row['errors']  && array_key_exists($field,$row['errors']))
                                                @foreach($row['errors'][$field] as $error)
                                                    <span style="display: block;font-size: 10px;color: #d30000;">{{$error}}</span>
                                                @endforeach
                                            @endif

                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif


            </div>
        </form>
    </div>
@stop
@push('scripts')
    <script>

        function setname() {
            const actualBtn = document.getElementById('actual-btn');
            const fileChosen = document.getElementById('file-chosen');
            fileChosen.textContent = actualBtn.files[0].name
        }

    </script>
@endpush