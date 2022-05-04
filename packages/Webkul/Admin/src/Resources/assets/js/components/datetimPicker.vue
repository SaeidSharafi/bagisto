<template>
    <div class="datapicker-wrapper">
        <date-picker
            format="YYYY-MM-DD HH:mm"
            type="datetime"
            compact-time
            clearable
            :value="initialValue"
            :placeholder="placeholder"
            locale="fa,en"
            :alt-name="name"
            alt-format="YYYY-MM-DD HH:mm"
            :max="maxDate"
            @input="dateChanged"
            append-to="#datepicker-placeholder"
            :locale-config="{
            fa: {
             displayFormat: 'dddd jDD jMMMM jYYYY HH:mm',
             lang: { label: 'شمسی' }
            },
            en: {
                displayFormat: 'YYYY-MM-DD HH:mm',
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
    props: ['clearable','inputFormat', 'displayFormat', 'name', 'id', 'placeholder', 'format', 'initialValue','maxDate'],
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
        window.console.log(this.maxDate)
    },
    methods: {
        dateChanged(event){
            this.selectedDate=event;
            this.$emit("onDateChange",event)
        }
    },
    components: {
        datePicker: VuePersianDatetimePicker
    }
}
</script>