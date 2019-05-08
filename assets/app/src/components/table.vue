<template>
    <div>
        <div>

            <b-form-group
                    horizontal
                    :label-cols="4"
                    description="Pesquise seu item aqui"
                    label="Pesquise aqui"
            >
                <b-form-input
                        id="input-2"
                        v-model="form.name"
                        required
                        placeholder="***"
                        @keydown.enter="onSearch"
                ></b-form-input>
            </b-form-group>
        </div>
        <vuetable ref="vuetable"
                  api-url="/imp/sorting/data"
                  :fields="columns"
                  :css="css.table"
                  pagination-path=""
                  :per-page="10"
                  :append-params="moreParams"
                  @vuetable:pagination-data="onPaginationData"
        ></vuetable>
        <vuetable-pagination ref="pagination"
                 :css="css.pagination"
                 @vuetable-pagination:change-page="onChangePage"
        ></vuetable-pagination>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuetable, {
        VuetablePagination,
        VuetablePaginationInfo,
    } from 'vuetable-2'
    import BForm from 'bootstrap-vue/es/components/form/form'
    import BFormGroup from 'bootstrap-vue/es/components/form-group/form-group'
    import BFormInput from 'bootstrap-vue/es/components/form-input/form-input'

    export default {
        components: {
            'vuetable': Vuetable,
            'vuetable-pagination': VuetablePagination,
            'b-form': BForm,
            'b-form-group': BFormGroup,
            'b-form-input': BFormInput
        },
        data() {
            return {
                moreParams: {
                    "filter": ''
                },
                form: {
                    name: ''
                },
                columns: [
                    {
                        name: "chrDescription",
                        sortField: "chrDescription",
                        title: "Descrição"
                    },
                    {
                        name: "chrCustomReference",
                        sortField: "chrCustomReference",
                        title: 'REF'
                    },
                    {
                        name: "intStock",
                        sortField: "intStock",
                        title: 'Estoque'
                    }
                ],
                items: [],
                css: {
                    table: {
                        tableClass: 'table table-striped table-bordered table-hovered',
                        loadingClass: 'loading',
                        ascendingIcon: 'glyphicon glyphicon-chevron-up',
                        descendingIcon: 'glyphicon glyphicon-chevron-down',
                        handleIcon: 'glyphicon glyphicon-menu-hamburger',
                    },
                    pagination: {
                        infoClass: 'pull-left',
                        wrapperClass: 'vuetable-pagination pull-right',
                        activeClass: 'btn-primary',
                        disabledClass: 'disabled',
                        pageClass: 'btn btn-border',
                        linkClass: 'btn btn-border',
                        icons: {
                            first: '',
                            prev: '',
                            next: '',
                            last: '',
                        },
                    }
                }
            }
        },
        methods: {
            setFilter (filterText) {
                this.moreParams = {
                    'filter': filterText
                };
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },
            onPaginationData(paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
            },
            onChangePage(page) {
                this.$refs.vuetable.changePage(page)
            },
            onSearch(e) {
                this.setFilter(e.target.value);
                return false;
            }
        }
    }
</script>