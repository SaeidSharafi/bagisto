<template>
    <div class="datapicker-wrapper">
        <date-picker
            format="YYYY-MM-DD"
            :clearable="clearable"
            :value="initialValue"
            :placeholder="placeholder"
            :auto-submit="autoSubmit"
            :popover="popover"
            locale="fa,en"
            :alt-name="name"
            alt-format="YYYY-MM-DD"
            :max="maxDate"
            @input="dateChanged"
            append-to="#datepicker-placeholder"
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
    props: ['clearable','autoSubmit','popover','inputFormat', 'displayFormat', 'name', 'id', 'placeholder', 'format', 'initialValue','maxDate'],
    $_veeValidate: {
        // fetch the current value from the innerValue defined in the component data.
        value () {
            return this.selectedDate;
        }
    },
    data: function () {
        return {
            date: this.initialValue ? this.initialValue : null,
            selectedDate: null
        }
    },
    mounted() {
        // window.console.log(this.maxDate)
    },
    methods: {
        dateChanged(event){
            // console.log(event);
            this.selectedDate=event;
            this.$emit("onDateChange",event)
        }
    },
    components: {
        datePicker: VuePersianDatetimePicker
    }
}
</script>