<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="m-0">Orders</h4>
                    <div>Manage all your orders</div>
                </div>
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
                            <table class="table table-bordered table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">User Detail</th>
                                    <th style="min-width: 120px">Product</th>
                                    <th style="min-width: 150px">Quantity</th>
                                    <th style="min-width: 150px">Price</th>
                                    <th style="min-width: 150px">Status</th>
                                    <th style="min-width: 150px">Date</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(order, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td><router-link :to="`/orders/${order.slug}`">{{order.name}}</router-link></td>
                                    <td>
                                        <span class="badge badge-light" v-for="(property) in order.properties">
                                            {{property.name}}
                                        </span>
                                    </td>
                                    <td></td>
                                    <td>{{order.updated_at | timeago}}</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" @click.prevent="openCreateModal(order)"
                                                   class="btn-link dropdown-item text-primary">Edit
                                                </a>
                                                <a href="#" @click.prevent="removeRecord(order, i)"
                                                   class="btn-link dropdown-item text-danger">Remove
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!dataBag || !dataBag.data.length">
                                    <td colspan="8" class="text-center">No orders to show
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
    import toast from "../services/toast";

    export default {
        name: "Order",
        components: { Pagination, LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processing: {
                    fetching: false
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
            handleModalClosed(data) {
                if (data && this.dataBag && this.dataBag.data) {
                    const index = this.getIndex(data);
                    if (index > -1) {
                        this.dataBag.data[index] = data;
                    } else {
                        this.dataBag.data.push(data);
                    }
                }

                this.selectedModal = null;
            },
            openCreateModal(item) {
                this.selectedModal = {
                    data: item
                };
            },
            removeRecord(item, i) {
                toast.confirm('Confirmation', 'Do you want to remove this order?', (result) => {
                    if (!result) {
                        return;
                    }

                    this.processing.deleting = true;

                    axios.delete(baseUrl + 'orders/' + item.slug).then(response => {
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
                        filters.push('from_date=' + this.filter.from);
                    }
                    if (this.filter.to) {
                        filters.push('to_date=' + this.filter.to);
                    }

                    this.lastSearchQuery = filters.length ? (filters.join('&')) : '';
                }

                this.getData((baseUrl + 'orders') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
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
