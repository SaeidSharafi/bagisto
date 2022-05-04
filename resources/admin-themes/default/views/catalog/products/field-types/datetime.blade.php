<div class="control">
    <p-date-picker
        v-validate="{{ $attribute->is_required ? 'required' : '' }}"
        data-vv-as="&quot;{{ $attribute->admin_name }}&quot;"
        name="{{ $attribute->code }}"
        id="{{ $attribute->code }}"
        clearable
        initial-value="{{  old($attribute->code) ?: $product[$attribute->code]}}"
        placeholder="&quot;{{ $attribute->admin_name }}&quot;"></p-date-picker>
</div>