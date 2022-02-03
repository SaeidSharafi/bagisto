{{--<script--}}
{{--    type="text/javascript"--}}
{{--    src="{{ asset('themes/velocity/assets/js/velocity-core.js') }}">--}}
{{--</script>--}}
{{--<script type="text/javascript" src="{{asset("js/velocity-core.js")}}"></script>--}}


<script type="text/javascript" src="{{ asset(mix('/js/manifest.js')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/velocity-core.js')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/components.js')) }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js"></script>
<script type="text/javascript" src="{{ asset(mix('/js/slider.js')) }}"></script>



<script type="text/javascript">
    (() => {
        /* activate session messages */
        let message = @json($velocityHelper->getMessage());
        if (message.messageType && message.message !== '') {
            window.showAlert(message.messageType, message.messageLabel, message.message);
        }

        /* activate server error messages */
        window.serverErrors = [];
        @if (isset($errors))
            @if (count($errors))
                window.serverErrors = @json($errors->getMessages());
            @endif
        @endif

        /* add translations */
        window._translations = @json($velocityHelper->jsonTranslations());
    })();
</script>

@stack('scripts')

<script>
    {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
</script>