@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.success.title') }}
@stop

@section('content-wrapper')

    <div id="player" style="width: 100vw;height: 90vh">

    </div>

@endsection
@push('scripts')
    <script src="https://app.spotplayer.ir/assets/js/app-api.js"></script>

    <script type="application/javascript">
        async function Play() {
            try {
                const sp = new SpotPlayer(document.getElementById('player'), '/spotx', false);
                await sp.Open('{{$spotLicense->key}}');
            } catch (ex) {
                console.log(ex);
            }
        }

        Play();
    </script>
@endpush