@php
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp
<div class="qoutes">
    <h4>
        درباره ما چه میگویند
    </h4>
    <teacher-collections
        locale-direction="{{ $direction }}">
    </teacher-collections>

</div>