<template>
    <div>
        <date-picker
            format="YYYY-MM-DD"
            :value="initialValue"
            :placeholder="placeholder"
            locale="fa,en"
            :alt-name="name"
            alt-format="YYYY-MM-DD"
            :max="maxDate"
            :locale-config="{
            fa: {
             displayFormat: 'dddd jDD jMMMM jYYYY',
             lang: { label: 'شمسی' }
            },
            en: {
                displayFormat: 'YYYY-MM-DD',
                lang: { label: 'Gregorian' }
           }
       }"
        ></date-picker>
    </div>
</template>

<script>

import VuePersianDatetimePicker from 'vue-persian-datetime-picker'

export default {
    name: 'persian-date-picker',
    props: ['inputFormat', 'displayFormat', 'name', 'id', 'placeholder', 'format', 'initialValue','maxDate'],
    data: function () {
        return {
            date: this.initialValue ? this.initialValue : null
        }
    },
    mounted() {
        window.console.log(this.maxDate)
    },
    components: {
        datePicker: VuePersianDatetimePicker
    }
}
</script>