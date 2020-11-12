<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="m-0">My Stores</h4>
                    <div>Manage all your stores in one view</div>
                </div>
                <router-link to="/my-stores/new" class="btn btn-outline-primary ml-3">Create Store</router-link>
            </div>
        </div>
        <div class="container page__container">
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Store</th>
                                    <th class="text-center" style="min-width: 100px">Total Products</th>
                                    <th class="text-center" style="min-width: 150px">Total Orders</th>
                                    <th class="text-center" style="min-width: 150px">Page Visit</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(store, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td>
                                        <a :href="store.url">{{store.name}}</a>
                                        <br>
                                        <span>{{store.slug}}</span>
                                    </td>
                                    <td class="text-center">{{store.total_products}}</td>
                                    <td class="text-center">{{store.total_orders}}</td>
                                    <td class="text-center">{{store.daily_visitors}} today / {{store.total_visitors}} total</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button :disabled="processing"
                                                        @click.prevent="manageCurrentStore(store, i)"
                                                        class="btn-link dropdown-item text-success">
                                                    Manage store
                                                </button>
                                                <router-link
                                                        :to="'/my-stores/' + store.slug"
                                                        class="btn-link dropdown-item text-success">
                                                    Edit details
                                                </router-link>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!processData.fetching && (!dataBag || !dataBag.data.length)">
                                    <td colspan="9" class="text-center">No products to show
                                    </td>
                                </tr>
                                <tr  v-if="processData.fetching">
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
    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        name: "products",
        components: {Pagination, LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processData: {
                    fetching: false,
                    updating: false,
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
        computed: {
          processing() {
              return this.processData.fetching || this.processData.updating;
          }
        },
        methods: {
            manageCurrentStore(store) {
                const prefStore = store.slug;
                const prefCurrency = store.currency ? store.currency.code : '₦';
                localStorage.setItem(appSetting.pref_store, prefStore);
                localStorage.setItem(appSetting.pref_currency, prefCurrency);

                setTimeout(() => {
                    location.href = "/";
                }, 100);
            },

            doSearch(pageIndex = 0) {
                if (!pageIndex) {
                    this.lastSearchQuery =  '';
                }

                this.getData((baseUrl + 'stores') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
            },
            getData(url) {
                this.processData.fetching = true;
                this.dataBag = null;
                axios.get(url).then(response => {
                    this.dataBag = response.data.data;
                    this.processData.fetching = false;
                }, error => {
                    this.processData.fetching = false;
                });
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
