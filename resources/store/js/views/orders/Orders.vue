<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div class="">
                    <h4 class="m-0">Orders</h4>
                    <div>Manage all your orders</div>
                </div>
            </div>
        </div>
        <div class="container page__container">
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <m-progress-bar v-if="isProcessing"></m-progress-bar>
                        <div class="px-3 mt-3">
                            Enter search criteria below
                        </div>
                        <div class="p-3">
                            <div class="input-group input-group-merge">
                                <input v-model="filter.filter" class="form-control" placeholder="Enter search term">
                                <input v-model="filter.from" class="form-control" type="date">
                                <input v-model="filter.to" class="form-control form-control-appended" type="date">
                                <button @click.prevent="doSearch()" class="btn btn-primary input-group-append">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Order No</th>
                                    <th style="min-width: 120px">User Detail</th>
                                    <th style="min-width: 120px">Contact</th>
                                    <th style="min-width: 120px">Items</th>
                                    <th style="min-width: 150px">Total</th>
                                    <th style="min-width: 150px">Status</th>
                                    <th style="min-width: 150px">Last Modified</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(order, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td><router-link :to="'/orders/' + order.slug">{{order.slug}}</router-link></td>
                                    <td>
                                        {{order.contact ? (order.contact.lastName + " " + order.contact.firstName) : ''}}
                                    </td>
                                    <td>
                                        <div><a v-if="order.contact && order.contact.email" :href="'mailto:' + order.contact.email">{{order.contact.email}}</a></div>
                                        <div><a v-if="order.contact && order.contact.phone" :href="'tel:' + order.contact.phone">{{order.contact.phone}}</a></div>
                                    </td>
                                    <td>
                                        {{order.item_count}} item{{order.item_count === 1 ? '' : 's'}}
                                    </td>
                                    <td>{{order.amount | currency}}</td>
                                    <td><span :class="'badge badge-' + order.status_color">{{order.status_description}}</span></td>
                                    <td>{{order.updated_at | timeago}}</td>
                                    <td>
                                        <div v-if="order.can_cancel" class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div v-if="order.requires_action" class="dropdown-menu dropdown-menu-right">
                                                <button :disabled="isProcessing" @click.prevent="removeRecord(order, i)"
                                                   class="btn-link dropdown-item text-danger">Cancel Order
                                                </button>
                                                <button v-if="order.can_pay" :disabled="processing"
                                                        @click.prevent="updateStatus('paid', order, i)"
                                                        class="btn-link dropdown-item text-primary">Mark as paid
                                                </button>
                                                <button v-if="order.can_be_delivered" :disabled="processing"
                                                        @click.prevent="updateStatus('delivered', order, i)"
                                                        class="btn-link dropdown-item text-success">Mark as delivered
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!processing.fetching && (!dataBag || !dataBag.data.length)">
                                    <td colspan="9" class="text-center">No orders to show
                                    </td>
                                </tr>
                                <tr  v-if="processing.fetching">
                                    <td colspan="9" class="text-center">
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
    import MProgressBar from "../../components/MProgressBar";

    export default {
        name: "Order",
        components: {MProgressBar, Pagination, LoadingSpinner, Upload, Spinner},
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
        computed: {
            isProcessing() {
                return this.processing.fetching || this.processing.deleting;
            }
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
            updateStatus(status, order, i) {
                if (this.processing) {
                    return;
                }
                toast.confirm('Confirmation', `Do you want to change this order status to ${status}?`, (value) => {
                    if (value) {
                        this.processData.status = true;
                        axios.put(`${baseUrl}orders/${order.slug}/status`, {status}).then(response => {
                            if (response.data.data) {
                                const index = this.getIndex(order);
                                if (index > -1) {
                                    this.dataBag.data[index] = response.data.data;
                                }
                            }
                            this.processData.status = false;
                        }).catch(error => {
                            this.processData.status = false;
                        });
                    }
                });
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
                        filters.push('from=' + this.filter.from);
                    }
                    if (this.filter.to) {
                        filters.push('to=' + this.filter.to);
                    }

                    this.lastSearchQuery = filters.length ? (filters.join('&')) : '';
                }

                this.getData((baseUrl + 'orders') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
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
