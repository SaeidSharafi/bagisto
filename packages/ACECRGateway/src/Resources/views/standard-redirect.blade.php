<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
در حال انتقال به درگاه پرداخت بانک ملت


<form action="{{ $payment_url }}" id="paypal_standard_checkout" method="POST">
    <input type="hidden" name="RefId" value="{{$refID}}">
    <input value="در صورت عدم انتقال بعد از 10 ثانیه کلیک کنید..." type="submit">

</form>

<script type="text/javascript">
    document.getElementById("paypal_standard_checkout").submit();
</script>
</body>