<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="m-0">Special Offers</h4>
                    <div>Manage special offers and product discounts</div>
                </div>
                <button @click.prevent="openCreateModal(false)" class="btn btn-outline-primary ml-3">Create Offers</button>
            </div>
        </div>
        <div class="container page__container">
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="px-3 mt-3">
                            Enter search criteria below
                        </div>
                        <div class="p-3">
                            <div class="input-group input-group-merge">
                                <input v-model="filter.filter" class="form-control" placeholder="Enter search term">
                                <input v-model="filter.from" class="form-control" type="date">
                                <input v-model="filter.to" class="form-control form-control-appended" type="date">
                                <button @click.prevent="doSearch()" class="btn btn-primary input-group-append px-3"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Title</th>
                                    <th style="min-width: 150px">Subtitle</th>
                                    <th style="min-width: 150px">Products</th>
                                    <th style="min-width: 150px">Banners</th>
                                    <th>Last Modified</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(offer, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td><router-link :to="`/special-offers/${offer.slug}`">{{offer.title}}</router-link></td>
                                    <td>
                                        {{offer.description}}
                                    </td>
                                    <td>{{offer.products_count || 'No'}} products</td>
                                    <td>{{offer.banners ? offer.banners.length : ''}}</td>
                                    <td>{{offer.updated_at | timeago}}</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" @click.prevent="openCreateModal(offer)"
                                                   class="btn-link dropdown-item text-primary">Edit
                                                </a>
                                                <button :disabled="processing.deleting" href="#" @click.prevent="removeRecord(offer, i)"
                                                   class="btn-link dropdown-item text-danger">Remove
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!processing.fetching && (!dataBag || !dataBag.data.length)">
                                    <td colspan="8" class="text-center">No offer to show
                                    </td>
                                </tr>
                                <tr  v-if="processing.fetching">
                                    <td colspan="8" class="text-center">
                                        <spinner></spinner>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <pagination v-if="dataBag" :data="dataBag" :fetcher="fetcher"></pagination>
            </div>
        </div>
    </div>

</template>

<script>
    import {getAuthUser} from "../../init-app";
    import Spinner from "../../components/Spinner";
    import Upload from "../../components/Upload";
    import LoadingSpinner from "../../components/LoadingSpinner";
    import Pagination from "../../components/Pagination";
    import toast from "../../services/toast";

    export default {
        name: "SpecialOffers",
        components: {Pagination, LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processing: {
                    fetching: false,
                    deleting: false,
                },
                selectedModal: null,
                storeLink: null,

                dataBag: null,
                fetcher: null,
                filter: {
                    filter: '',
                    to: '',
                    from: ''
                },
            }
        },
        mounted() {
            this.fetcher = {search: this.doSearch, fetch: this.getData};
            this.authUser = getAuthUser();
            this.doSearch();
        },
        methods: {
            getIndex(data) {
                return this.dataBag.data.findIndex(d => d.id.toString() === data.id.toString());
            },
            openCreateModal(item) {
               this.$router.push('/special-offers/' + (item ? item.slug : 'new'))
            },
            removeRecord(item, i) {
                toast.confirm('Confirmation', 'Do you want to delete this offer. Note that all the' +
                    ' products added to this offer will be affected and that the process is irreversible.', (result) => {
                    if (!result) {
                        return;
                    }

                    this.processing.deleting = true;

                    axios.delete(baseUrl + 'special-offers/' + item.slug).then(response => {
                        const index = this.getIndex(data);
                        if (index > -1) {
                            this.dataBag.data.splice(index, 1);
                        }
                        this.processing.deleting = false;
                    }).catch(error => {
                        this.processing.deleting = false;
                    });
                });
            },
            doSearch(pageIndex = 0) {
                if (!pageIndex) {
                    const filters = [];
                    if (this.filter.filter) {
                        filters.push('filter=' + this.filter.filter);
                    }
                    if (this.filter.from) {
                        filters.push('from=' + this.filter.from);
                    }
                    if (this.filter.to) {
                        filters.push('to=' + this.filter.to);
                    }

                    this.lastSearchQuery = filters.length ? (filters.join('&')) : '';
                }

                this.getData((baseUrl + 'special-offers') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
            },
            getData(url) {
                this.processing.fetching = true;
                this.dataBag = null;
                axios.get(url).then(response => {
                    this.dataBag = response.data.data;
                    this.processing.fetching = false;
                }, error => {
                    this.processing.fetching = false;
                });
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
