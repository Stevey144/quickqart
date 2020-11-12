<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <h4 class="m-0">Products</h4>
                <router-link to="/products/new" class="btn btn-outline-primary ml-3">Add new product</router-link>
            </div>
        </div>
        <div class="container page__container">
            <div v-if="!processing.fetching" class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="p-3">
                            <div class="input-group input-group-merge">
                                <input v-model="filter.filter" class="form-control" placeholder="Enter search term">
                                <input v-model="filter.from" class="form-control" type="date">
                                <input v-model="filter.to" class="form-control form-control-appended" type="date">
                                <button @click.prevent="doSearch()" class="btn btn-primary input-group-append">Search</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bproducted table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Name</th>
                                    <th style="min-width: 100px">Category</th>
                                    <th style="min-width: 150px">Specifications</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Last Modified</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(product, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td><router-link :to="`/products/${product.slug}`">{{product.name}}</router-link></td>
                                    <td>{{product.category ? product.category.name : ''}}</td>
                                    <td>
                                        <span v-for="(property) in product.properties">
                                            {{property.name}} {{property.value}}
                                        </span>
                                    </td>
                                    <td>{{product.price | currency}}</td>
                                    <td>{{product.quantity}}</td>
                                    <td>{{product.updated_at | timeago}}</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#"
                                                   @click.prevent="''"
                                                   class="btn-link dropdown-item text-danger">Remove
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!dataBag || !dataBag.data.length">
                                    <td colspan="8" class="text-center">No products to show
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <pagination v-if="dataBag" :data="dataBag" :fetcher="fetcher"></pagination>
            </div>
            <div v-if="processing.fetching" class="row">
                <div class="col-md-12">
                    <div class="text-center py-5 mt-5">
                        <loading-spinner></loading-spinner>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
    import {getAuthUser} from "../init-app";
    import Spinner from "../components/Spinner";
    import Upload from "../components/Upload";
    import LoadingSpinner from "../components/LoadingSpinner";
    import Pagination from "../components/Pagination";

    export default {
        name: "products",
        components: {Pagination, LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processing: {
                    fetching: false
                },
                selectedModal: {
                    item: null,
                    type: null,
                    index: null
                },
                storeLink: null,

                dataBag: null,
                fetcher: null,
                filter: {
                    filter: '',
                    to: '',
                    from: ''
                }
            }
        },
        mounted() {
            this.fetcher = {search: this.doSearch, fetch: this.getData};
            this.authUser = getAuthUser();
            this.doSearch();
        },
        methods: {
            doSearch(pageIndex = 0) {
                if (!pageIndex) {
                    const filters = [];
                    if (this.filter.filter) {
                        filters.push('filter=' + this.filter.filter);
                    }
                    if (this.filter.from) {
                        filters.push('from_date=' + this.filter.from);
                    }
                    if (this.filter.to) {
                        filters.push('to_date=' + this.filter.to);
                    }

                    this.lastSearchQuery = filters.length ? (filters.join('&')) : '';
                }

                this.getData((baseUrl + 'products') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
            },
            getData(url) {
                this.processing.fetching = true;
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
