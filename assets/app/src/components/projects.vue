<template>
    <div>
        <div class="header-projects"
             :style="'background: url(\'' + wpbg + '\')'">
            <div class="filter">
                <div class="filter-fields">
                    <h3>Filtrar projetos {{ year }}</h3>
                    <div class="row">
                        <div class="col-3">
                            <label for="field-program">Programa</label>
                            <select
                                    name="program"
                                    v-model="program.selected"
                                    id="field-program"
                                    :disabled="isLoading"
                                    v-on:change="onSelectFilter">
                                <option value="" selected>- Programa -</option>
                                <option v-for="(v, k) in program.options" :value="k" :key="k">
                                    {{ v }}
                                </option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="field-company">Cooperativa</label>
                            <select
                                    name="company"
                                    v-model="company.selected"
                                    id="field-company"
                                    :disabled="isLoading"
                                    v-on:change="onSelectFilter">
                                <option value="" selected>- Cooperativa -</option>
                                <option v-for="(v, k) in company.options" :value="k" :key="k">
                                    {{ v }}
                                </option>
                            </select>
                        </div>
                        <div class="col-1">
                            <label for="state">Estado</label>
                            <select
                                    name="uf"
                                    v-model="state.selected"
                                    id="state"
                                    :disabled="isLoading"
                                    v-on:change="onSelectState">
                                <option value="" selected>UF</option>
                                <option v-for="(v, k) in state.options" :value="v" :key="k">
                                    {{ v }}
                                </option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="city_id">Cidade</label>
                            <select
                                    v-model="city.selected"
                                    name="city_id"
                                    :disabled="(state.selected === '' || isLoading)"
                                    id="city_id"
                                    v-on:change="onSelectFilter">
                                <option v-if="isLoading" value="0" selected>Carregando...</option>

                                <option v-if="!isLoading && state.selected.length > 0" value="0" selected>- Municípios -
                                </option>
                                <option v-if="!isLoading && state.selected.length < 1" value="0" selected>- Selecione o
                                    estado -
                                </option>
                                <option v-for="(v, k) in city.options" :value="k" :key="k">
                                    {{ v }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nc_projects_wrapper">
            <div class="projects-list row">
                <article class="project projects type-projects status-publish has-post-thumbnail"
                         v-for="p in project.collection">
                    <div class="project-inner">
                        <div class="thumb">
                            <a :href="'/projeto/' + p.id + '/' + p.slug">
                                <img width="300" height="166"
                                     :src="p.thumb"
                                     class="attachment-projects-thumb size-projects-thumb wp-post-image"
                                     alt=""
                                >
                            </a>
                        </div>
                        <div class="about">
                            <h3 class="project-title">
                                <a :href="'/projeto/' + p.id + '/' + p.slug">
                                    {{ p.project }}
                                </a>
                            </h3>
                            <div class="project-school">{{ p.school }}</div>
                        </div>
                        <div class="city">{{ p.city }} - {{ p.uf }}</div>
                    </div>
                </article>
            </div>

            <div class="projects-pagenavi row">
                <pagination :data="paginationData" @pagination-change-page="getResults"></pagination>
            </div>
        </div>

    </div>

</template>

<script>
    import {mapActions} from 'vuex';
    import axios from 'axios';

    export default {
        props: ['wpbg', 'year'],
        data() {
            return {
                isLoading: false,
                project: {
                    collection: []
                },
                state: {
                    selected: '',
                    options: []
                },
                city: {
                    selected: 0,
                    options: []
                },
                program: {
                    selected: '',
                    options: []
                },
                company: {
                    selected: '',
                    options: []
                },
                paginationData: {},
                self: this,
                noProjects: false,
                filter: ""
            }
        },

        mounted() {
            this.isLoading = true;

            this.getResults();

            /*
            axios
                .get(apiUrl + "/uf")
                .then((response) => {
                    this.state.options = response.data
                    // this.setStates(this.state.options)
                })
                .finally(() => this.isLoading = false)
                */

        },

        // computed: {
        //   ...mapState({
        //     listStates: state => state.state.options
        //   })
        // },

        methods: {
            ...mapActions(['setStates']),

            onSelectState: function () {
                this.isLoading = true;
                this.city.options = [];
                if (this.state.selected.length < 1)
                    this.city.selected = 0;

                this.getResults(1);
            },

            onSelectFilter: function () {
                this.getResults(1);
            },


            onFilter: function () {
                this.getResults(1);
            },

            getResults: function (_page = 1) {

                this.projectList = [];
                this.isLoading = true

                let _params = {page: _page};

                if (this.state.selected.length)
                    _params.uf = this.state.selected;

                if (this.program.selected.length)
                    _params.program = this.program.selected;

                if (this.city.selected && this.city.selected > 0)
                    _params.city = this.city.selected;

                if (this.company.selected)
                    _params.company = this.company.selected;

                if (parseInt(this.year) > 0)
                    _params.year = this.year;

                axios
                    .get(apiUrl + "projects", {params: _params})
                    .then((response) => {
                        this.noProjects = response.data.data.length < 1;
                        this.project.collection = response.data.data;
                        this.state.options = response.data.filters.states;
                        this.city.options = response.data.filters.cities;
                        this.program.options = response.data.filters.programs;
                        this.company.options = response.data.filters.companies;
                        //this.year.options = response.data.filters.years;
                        let pData = response.data;
                        pData.data = {};
                        this.paginationData = pData;
                    })
                    .finally(() => this.isLoading = false)
            }
        }
    }
</script>

<style lang="scss">
    .header-projects .filter {
        width: 1298px;
    }

    .header-projects .filter h3 {
        color: #888;
        font-weight: bold;
        padding-bottom: 15px;
    }

    .header-projects .filter > div {
        background-color: #fff;
        padding: 25px 50px;
        border-radius: 5px;
    }

    .header-projects .filter label {
        display: none;
    }

    .header-projects .filter select {
        display: block;
        max-width: 100%;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        box-shadow: 0 0 0 0 transparent inset;
        padding: .6em .8em;
        color: rgba(0, 0, 0, 0.5);
    }

    .header-projects .filter select, .header-projects .filter input {
        height: 40px;
        width: 100%;
    }

    $thumb-border-radius: 10px;
    .nc_projects_wrapper {

        .pagination {
            display: -ms-flexbox;
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
            margin: 0 auto;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-link:hover {
            z-index: 2;
            color: #0056b3;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-link:focus {
            z-index: 2;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .page-item:last-child .page-link {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .projects-list {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display:flex;
            flex-flow :row wrap;
            justify-content: space-around;
            align-items: stretch;
            .project {
                display: flex;
                height: auto;
                background-color: #fff;
                border: 1px solid #ccc;
                -webkit-border-bottom-right-radius: $thumb-border-radius;
                -webkit-border-bottom-left-radius: $thumb-border-radius;
                -moz-border-radius-bottomright: $thumb-border-radius;
                -moz-border-radius-bottomleft: $thumb-border-radius;
                border-bottom-right-radius: $thumb-border-radius;
                border-bottom-left-radius: $thumb-border-radius;
                .project-inner {
                    display:flex;
                    flex-direction:column;
                    .thumb {
                        margin-top: -1px;
                        margin-left: -1px;
                        margin-right: -1px;
                        background-color: #fff;
                    }
                    .about {
                        padding: 10px 20px 0 20px;
                    }
                    .city {
                        padding: 10px 20px 10px 20px;
                        margin-top: auto;
                        text-align: right;
                    }
                    .city:before {
                        display: none;
                    }
                }
            }
        }
    }
</style>
<style lang="scss" scoped>
    .is-loading {
        padding: 15px
    }

</style>
