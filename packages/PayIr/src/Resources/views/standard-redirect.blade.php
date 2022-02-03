<?php //$payir = app(\PayIr\Payment\PayIr::class) ?>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the PayPal website in a few seconds.


    <form action="{{ route('paydotir.redirect') }}" id="paypal_standard_checkout" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">

    </form>

    <script type="text/javascript">
        document.getElementById("paypal_standard_checkout").submit();
    </script>
</body>