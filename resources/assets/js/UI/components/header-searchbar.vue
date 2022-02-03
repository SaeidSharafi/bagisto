<template>
    <div class="btn-group full-width force-center">
        <div
            class="btn"
            id="header-search-icon"
            aria-label="Search"
            @click="submitForm"
        >
            <i class="fs16 fw6 rango-search"></i>
        </div>
        <input
            required
            name="term"
            type="search"
            class="form-control"
            :placeholder="__('header.search-text')"
            aria-label="Search"
            v-model:value="inputVal"
        />

        <slot name="image-search"></slot>


    </div>
</template>

<script type="text/javascript">
export default {
    data: function() {
        return {
            inputVal: '',
            searchedQuery: []
        };
    },

    created: function() {
        let searchedItem = window.location.search.replace('?', '');
        searchedItem = searchedItem.split('&');

        let updatedSearchedCollection = {};

        searchedItem.forEach(item => {
            let splitedItem = item.split('=');
            updatedSearchedCollection[splitedItem[0]] = decodeURI(
                splitedItem[1]
            );
        });

        if (updatedSearchedCollection['image-search'] == 1) {
            updatedSearchedCollection.term = '';
        }

        this.searchedQuery = updatedSearchedCollection;

        if (this.searchedQuery.term) {
            this.inputVal = decodeURIComponent(
                this.searchedQuery.term.split('+').join(' ')
            );
        }
    },

    methods: {
        focusInput: function(event) {
            $(event.target.parentElement.parentElement)
                .find('input')
                .focus();
        },

        submitForm: function() {
            if (this.inputVal !== '') {
                $('input[name=term]').val(this.inputVal);
                $('#search-form').submit();
            }
        }
    }
};
</script>
