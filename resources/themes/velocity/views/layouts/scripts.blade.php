<script type="text/javascript" src="{{ asset(mix('/js/manifest.js')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/velocity-core.js')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/velocity.js')) }}"></script>

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


        let timerElement = document.getElementById("countdown-timer");

        if (timerElement) {

            var date = timerElement.dataset.countdown;
            var countDownDate = new Date(date).getTime();
            console.log(date);
            console.log(countDownDate);

            var x = setInterval(function () {
                let timerElement = document.getElementById("countdown-timer");
                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                if (!countDownDate) {
                    timerElement.innerHTML = "0:00:00:00";
                    return;
                }
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.innerHTML = "0:00:00:00";
                // Display the result in the element with id="demo"
                timerElement.innerHTML = days + ":" + ("0" + hours).slice(-2) + ":"
                    + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);

                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    timerElement.innerHTML = "EXPIRED";
                }
            }, 1000);
        }
    })();

    /**
     * Wishist form will dynamically create and execute.
     *
     * @param {!string} action
     * @param {!string} method
     * @param {!string} csrfToken
     */
    function submitWishlistForm(action, method, isConfirm, csrfToken) {
        if (isConfirm && ! confirm('{{ __('shop::app.checkout.cart.cart-remove-action') }}')) return;

        let form = document.createElement('form');
            form.method = 'POST';
            form.action = action;

        let _methodElement = document.createElement('input');
            _methodElement.type = 'hidden';
            _methodElement.name = '_method';
            _methodElement.value = method;
            form.appendChild(_methodElement);

        let _tokenElement = document.createElement('input');
            _tokenElement.type = 'hidden';
            _tokenElement.name ='_token';
            _tokenElement.value = csrfToken;
            form.appendChild(_tokenElement);

        document.body.appendChild(form);
        form.submit();
    }
</script>

@stack('scripts')

<script>
    {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
</script>
