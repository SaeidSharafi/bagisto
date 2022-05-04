<div class="control">
<p-date-picker name="{{ $attribute->code }}"
               id="{{ $attribute->code }}"
               initial-value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
               v-validate="{{ $attribute->is_required ? 'required' : '' }}"
               data-vv-as="&quot;{{ $attribute->admin_name }}&quot;"
               clearable
               placeholder="{{ $attribute->admin_name }}"></p-date-picker>
</div>