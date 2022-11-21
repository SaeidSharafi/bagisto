<multiselect></multiselect>



@push('scripts')

    <script type="text/x-template" id="multiselect-template">
        <div style="width: 70%">
            <v-select dir="rtl" v-model="selected" label="name" :filterable="false" :options="options" @search="onSearch">
                <template slot="option" slot-scope="option">
                    <div class="d-center">
                        @{{ option.name }}
                    </div>
                </template>
                <template slot="selected-option" slot-scope="option">
                    <div class="selected d-center">
                        @{{ option.name }}
                    </div>
                </template>
                <template slot="no-options">
                    هیچ موردی یافت نشد
                </template>
            </v-select>
            <input v-validate="'{{ $validations }}'" type="hidden" id="teacher_id" name="teacher_id" :value="(selected) ? selected.id : ''"
                   data-vv-as="&quot;{{ $attribute->admin_name }}&quot;">
        </div>
    </script>

    <script>

        Vue.component('multiselect', {

            template: '#multiselect-template',
            inject: ['$validator'],
            data: function () {
                return {
                    options: [],
                    selected: {},
                }
            },
            @if($selected_teacher)
            created: function () {
                this.selected = {id: {{$selected_teacher->id}}, name: '{{$selected_teacher->name}}'}
            },
            @endif
            methods: {
                onSearch(search, loading) {
                    if (search.length) {
                        loading(true);
                        this.search(loading, search, this);
                    }
                },
                search: _.debounce((loading, search, vm) => {
                    fetch(
                        `{{route('admin.catalog.products.teachersearch')}}?query=${search}`
                    ).then(res => {
                        res.json().then(json => (vm.options = json));
                        loading(false);
                    });
                }, 350)
            }
        });

    </script>

@endpush