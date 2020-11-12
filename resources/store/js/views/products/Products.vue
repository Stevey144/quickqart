<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="m-0">Products</h4>
                    <div>Manage your products</div>
                </div>
                <router-link to="/products/new" class="btn btn-outline-primary ml-3">Add new product</router-link>
            </div>
        </div>
        <div class="container page__container">
            <div class="alert alert-info d-flex justify-content-between card-margin p-2" role="alert">
                <i class="material-icons mr-3" style="width: 20px">info_circle</i>
                <div class="text-body w-100"><strong>Featured products</strong> appear first on search list. Add your products to featured product list to make them more prominent and give them greater chance to show in search for your customers</div>
            </div>
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <m-progress-bar v-if="processing"></m-progress-bar>
                        <div class="px-3 mt-3">
                            Enter search criteria below
                        </div>
                        <div class="p-3">
                            <div class="input-group input-group-merge">
                                <input v-model="filter.filter" class="form-control" placeholder="Enter search term">
                                <input v-model="filter.from" class="form-control" type="date">
                                <input v-model="filter.to" class="form-control form-control-appended" type="date">
                                <button :disabled="processing" @click.prevent="doSearch()" class="btn btn-primary input-group-append">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Name</th>
                                    <th style="min-width: 100px">Category</th>
                                    <th style="min-width: 150px">Specifications</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Last Modified</th>
                                    <th>Hide</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(product, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td>
                                        <div v-if="product.is_featured"><span class="badge badge-soft-success">Featured</span></div>
                                        <router-link :to="`/products/${product.slug}/view`">
                                        {{product.name}}</router-link></td>
                                    <td>{{product.category ? product.category.name : ''}}</td>
                                    <td>
                                        <span class="badge badge-purple mr-1" v-for="(property) in product.properties">
                                            {{property.name}}: {{property.value}}
                                        </span>
                                    </td>
                                    <td>{{product.price | currency}}</td>
                                    <td>{{product.quantity}}</td>
                                    <td>{{product.updated_at | timeago}}</td>
                                    <td>
                                        <div class="position-relative">
                                            <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                                <input :disable="processing" :readonly="processing"
                                                       :checked="!product.active" @change="updateIsActive(product, i)"
                                                       type="checkbox" :id="'toggle_active_' + product.id" class="custom-control-input">
                                                <label class="custom-control-label" :for="'toggle_active_' + product.id"></label>
                                            </div>
                                            <div v-if="processing" style="left: -10px; top: -2px; position: absolute; width: 120%; height: 120%; background: rgba(255, 255, 255, 0)"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <inventory :product="product" :i="i" :processing="processing"
                                                           v-on:updating-inventory="($event) => processData.inventory = $event"
                                                           v-on:inventory-updated="($event) => updateQuantity(i, $event)"
                                                ></inventory>
<!--                                                <router-link :to="`/products/${product.slug}/view`"-->
<!--                                                             class="btn-link dropdown-item text-primary">View detail-->
<!--                                                </router-link>-->
                                                <button :disabled="processing" v-if="!product.is_featured"
                                                        @click.prevent="updateFeatured(product, i)"
                                                        class="btn-link dropdown-item">
                                                    Tag as featured product
                                                </button>
                                                <router-link :to="`/products/${product.slug}`"
                                                             class="btn-link dropdown-item text-success">Edit details
                                                </router-link>
                                                <button :disabled="processing" v-if="product.is_featured"
                                                        @click.prevent="updateFeatured(product, i)"
                                                        class="btn-link dropdown-item text-danger">
                                                    Remove from featured list
                                                </button>
                                                <button :disabled="processing" @click.prevent="deleteProduct(product, i)"
                                                   class="btn-link dropdown-item text-danger">Delete product
                                                </button>
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
    import Inventory from "./Inventory";
    import MProgressBar from "../../components/MProgressBar";

    export default {
        name: "products",
        components: {MProgressBar, Inventory, Pagination, LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processData: {
                    fetching: false,
                    deleting: false,
                    featured: false,
                    activeness: false,
                    inventory: false,
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
              return this.processData.fetching || this.processData.deleting ||
                  this.processData.featured || this.processData.inventory || this.processData.activeness;
          }
        },
        methods: {
            updateQuantity(i, total) {
                try {
                    this.dataBag.data[i].quantity = total;
                } catch (e) {
                }
            },
            deleteProduct(product, i) {
                if (this.processData.deleting) {
                    return;
                }
                toast.confirm('Confirmation', 'Do you want to delete this product?', (value) => {
                    if (value) {
                        this.processData.deleting = true;
                        axios.delete(`${baseUrl}products/${product.slug}`).then(response => {
                            if (response.data && response.data.data) {
                                this.dataBag.data.splice(i, 1);
                            }
                            this.processData.deleting = false;
                        }).catch(error => {
                            this.processData.deleting = false;
                        });
                    }
                });
            },
            updateIsActive(product, i) {
                if (this.processData.activeness) {
                    return;
                }
                this.processData.activeness = true;
                axios.put(`${baseUrl}products/${product.slug}/activeness`).then(response => {
                    if (response.data && response.data.data) {
                        this.dataBag.data[i] = response.data.data;
                    }
                    this.processData.activeness = false;
                }).catch(error => {
                    this.processData.activeness = false;
                });
            },
            updateFeatured(product, i) {
                if (this.processData.featured) {
                    return;
                }
                this.processData.featured = true;
                axios.put(`${baseUrl}products/${product.slug}/featured`).then(response => {
                    if (response.data && response.data.data) {
                        this.dataBag.data[i] = response.data.data;
                    }
                    this.processData.featured = false;
                }).catch(error => {
                    this.processData.featured = false;
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

                this.getData((baseUrl + 'products') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
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
