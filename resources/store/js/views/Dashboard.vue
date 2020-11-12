<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <h4 class="m-0">Welcome</h4>
                <a :href="storeLink" target="_blank" class="btn btn-outline-primary ml-3 px-3">
                    Go to Store <i class="material-icons">arrow_forward</i></a>
            </div>
        </div>

        <div class="container page__container">
            <div v-if="!processing.fetching" class="row card-group-row">
                <div class="col-lg-4 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Categories</div>
                            <div class="text-amount">{{dashboardData.categories}}</div>
                        </div>

                        <div class="avatar">
                                <span class="bg-soft-success avatar-title rounded-circle text-center text-success">
                                    <i class="material-icons icon-40pt">list</i>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Products</div>
                            <div class="text-amount">{{dashboardData.products}}</div>
                        </div>
                        <div class="avatar">
                                <span class="bg-soft-primary avatar-title rounded-circle text-center text-primary">
                                    <i class="material-icons icon-40pt">view_carousel</i>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Orders</div>
                            <div class="text-amount">{{dashboardData.orders}}</div>
<!--                            <div class="text-stats text-danger">30 since last week</div>-->
                        </div>
                        <div class="avatar">
                            <span class="bg-soft-warning avatar-title rounded-circle text-center text-primary">
                                <i class="material-icons icon-40pt">shopping_cart</i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHART -->
            <div v-if="!processing.fetching" class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-white d-flex align-items-center">
                            <h4 class="card-header__title mb-0">New orders</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
<!--                                    <th style="min-width: 120px">Order No</th>-->
                                    <th style="min-width: 120px">Customer</th>
                                    <th style="min-width: 120px">Contact Information</th>
                                    <th style="min-width: 120px">Items</th>
                                    <th style="min-width: 150px">Status</th>
                                    <th style="min-width: 150px">Total</th>
                                    <th style="min-width: 150px">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(order, i) in (orders || [])">
                                    <td>{{ (i + 1)}}</td>
<!--                                    <td><router-link :to="'/orders/' + order.slug">{{order.slug}}</router-link></td>-->
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
                                    <td><span :class="'badge badge-' + order.status_color">{{order.status_description}}</span></td>
                                    <td>{{order.amount | currency}}</td>
                                    <td>{{order.updated_at | timeago}}</td>
                                </tr>
                                <tr v-if="!(orders || []).length">
                                    <td colspan="8" class="text-center">No orders to show
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
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

    export default {
        name: "Dashboard",
        components: {LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                orders: [],
                processing: {
                    fetching: false
                },
                selectedModal: {
                    item: null,
                    type: null,
                    index: null
                },
                storeLink: '',
                dashboardData: {
                }
            }
        },
        mounted() {
            this.authUser = getAuthUser();
            this.getDashboardData();
        },
        methods: {
            getDashboardData() {
                this.processing.fetching = true;

                axios.get(baseUrl + 'dashboard').then(response => {
                    if (response.data) {
                        this.dashboardData = response.data.stats;
                        this.storeLink = response.data.store_link;

                        console.log(this.storeLink);
                        this.orders = response.data.orders;
                    }
                    this.processing.fetching = false;
                }).catch(error => {
                    this.processing.fetching = false;
                });
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
