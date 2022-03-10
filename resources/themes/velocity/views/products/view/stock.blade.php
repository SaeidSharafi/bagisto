{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}
<div slot="body">
    <table class="full-specifications availability">
        <tr>
            <td class="fw6">
                ظرفیت باقیمانده:
            </td>
            <td  class="fw6">
                @if ( $product->haveSufficientQuantity(1) === true )
                    {{$product->totalQuantity()}}
                @else

                    <span class="text-danger"> {{ __('shop.product.out-of-stock') }}</span>

                @endif
            </td>
        </tr>
    </table>
<div class="col-12 availability">
    <div class="row">
        <div class="col-md-6 fs16">

        </div>
        <div class="col-md-6">

        </div>
    </div>

</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}