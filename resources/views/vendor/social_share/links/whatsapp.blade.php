@php
    /**
     * @var Webkul\Product\Models\ProductFlat $product
     * @var string $message
     */
    $url = route('shop.productOrCategory.index', $product->url_key);
    $whatsapp_url = 'whatsapp://send?' . http_build_query([
        'text' => $message . ' ' . $url,
    ]);
@endphp

<whatsapp-share></whatsapp-share>

@push('css')
    <style>
        .bb-social--whatsapp a svg > path {
            fill: #25D366;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/x-template" id="whatsapp-share-link">
        <li class="bb-social-share__item bb-social--whatsapp" v-if="isMobile">
            <a :href="shareUrl" data-action="share/whatsapp/share" target="_blank">
                @include('social_share::icons.whatsapp')
            </a>
        </li>
    </script>

    <script type="text/javascript">
        Vue.component('whatsapp-share', {
            template: '#whatsapp-share-link',
            data: function () {
                return {
                    shareUrl: '{{ $whatsapp_url }}'
                }
            }
        });
    </script>
@endpush