<div class="footer">
    <div class="footer-content">
        @include('shop.footer.maps')

        @include('shop.footer.main')


        @if (core()->getConfigData('general.content.footer.footer_toggle'))
            @include('shop::layouts.footer.copy-right')
        @endif
    </div>
</div>


