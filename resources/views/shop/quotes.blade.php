@php
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp
<div class="qoutes">
    <h4>
        درباره ما چه میگویند
    </h4>

    <carousel-component
        slides-per-page="1"
        pagination-enabled="true"
        navigation-enabled="hide"
        add-class="qoute-carosuel"
        locale-direction="{{ $direction }}"
        :slides-count="4">

        <slide :slot="`slide-${index}`" v-for="index in 4" v-bind:key="index">
            <div>
                <div class="qoute-avatar">
                    <img src="{{asset('images/q-avatar.png')}}">
                </div>
                <p class="qoute-text">
                    علی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی دعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای
                    کاربردی می باشد، کتابهای زیادی دعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی د
                </p>
                <p class="qoute-author">
                    <span class="name">علی قربانی</span>
                    <span class="role">مدرس شبکه های کامپیوتری</span>
                </p>
            </div>
        </slide>
    </carousel-component>
</div>