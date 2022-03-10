{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}


    @if ($customAttributeValues)

            <div slot="header" class="mb-3">
                <h6 class="no-margin display-inbl fw7">
                    {{ __('shop.attributes.course_details') }}
                </h5>
            </div>

            <div slot="body">
                <table class="full-specifications">
                    @foreach ($customAttributeValues as $attribute)
                        @if ($attribute['code'] == "teacher")
                            @continue
                        @endif
                        <tr>
                            @if ($attribute['label'])
                                <td class='fw6'>{{ $attribute['label'] }}:</td>
                            @else
                                <td>{{ $attribute['admin_name'] }}</td>
                            @endif

                            @if ($attribute['type'] == 'file' && $attribute['value'])
                                <td class="fw6">
                                    <a  href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}" style="color:black;">
                                        <i class="icon rango-download-1"></i>
                                    </a>
                                </td>
                            @elseif ($attribute['type'] == 'image' && $attribute['value'])
                                <td>
                                    <a href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                                        <img src="{{ Storage::url($attribute['value']) }}" style="height: 20px; width: 20px;" alt=""/>
                                    </a>
                                </td>
                            @else
                                <td  class="fw6">{{ $attribute['value'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
    @endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}