<template>
    <div style="min-height: calc(100vh - 155px)">
        <div v-if="!processing.fetching && product"
             class="p-4 mb-4"
             style="min-height:300px">
            <div class="row d-flex justify-content-center">
                <div class="col-md-5 text-md-right text-sm-center">
                    <div class="has-bg-img" :style="`background-image: url(${product.cover})`">
                    </div>
                </div>
                <div class="col-md-6 text-md-left text-sm-center text-xs-center">
                    <h5 class="text-primary">{{product.name | preview(80)}}</h5>
                    <div class=""
                         v-html="`<small>` + extractProperties(product.properties) + `</small>`"></div>
                    <div class="text-dark">
                        <small>{{(product.description || 'No description') | preview(60)}}</small>
                    </div>
                    <div class="mt-2">
                        <span class="font-weight-bold">{{product.price | currency}} per unit</span>
                    </div>
                    <div class="mt-2">
                        <span :class="product.quantity ? '' : 'text-danger'"
                              class="font-weight-bold">{{product.quantity + ' '}} units available</span>
                    </div>
                    <button :disabled="processing.addingInventory" style="min-width: 100px" class="btn btn-sm btn-outline-primary mt-3"
                            @click="addToInventory()"
                    ><span v-if="!processing.addingInventory"><i class="fa fa-plus mr-2"></i> Update Inventory</span>
                        <spinner v-else></spinner>
                    </button>
                    <inventory v-on:updating-inventory="($event) => processing.addingInventory = $event"
                               v-on:inventory-updated-data="handleUpdateInventory" :product="product"
                               style="display: none;"></inventory>
                </div>
            </div>
        </div>
        <div class="container page__container">
            <div v-if="!processing.fetching" class="row no-gutters">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white">
                            <h4 class="card-header__title">Inventory/Purchase History</h4>
                        </div>
                        <div class="card-header">
                            <form @submit.prevent="getInventory()" class="form-inline text-center">
                                <label class="mr-sm-2">Filter between:</label>
                                <input type="date" v-model="form.from" class="form-control mb-2 mr-sm-2 mb-sm-0">
                                <input type="date" v-model="form.to" class="form-control mb-2 mr-sm-2 mb-sm-0">
                                <button :disabled="processing.inventory" class="btn btn-outline-primary px-4">Filter
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class=" text-primary">
                                    <tr>
                                        <th class="except-sm" style="width: 40px">#</th>
                                        <th style="min-width: 120px">Quantity</th>
                                        <th style="min-width: 120px">Date</th>
                                        <th style="min-width: 150px">Type</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(inventory, i) in (inventories)">
                                        <td>{{ (i + 1)}}</td>
                                        <td>{{inventory.quantity}}</td>
                                        <td>{{inventory.created_at | timeago}}</td>
                                        <td class="text-capitalize">{{inventory.type}}</td>
                                    </tr>
                                    <tr v-if="!inventories.length && !processing.inventory">
                                        <td colspan="4" class="text-center">
                                            No stock or purchase history
                                        </td>
                                    </tr>
                                    <tr v-if="processing.inventory">
                                        <td colspan="4" class="text-center">
                                            <spinner></spinner>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="processing.fetching" class="row no-gutters">
                <div class="col-12 text-center py-5 mt-5">
                    <loading-spinner></loading-spinner>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LoadingSpinner from "../../components/LoadingSpinner";
    import Spinner from "../../components/Spinner";
    import toast from "../../services/toast";
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    import Inventory from "./Inventory";

    export default {
        name: "ViewProductDetail",
        components: {Inventory, Spinner, LoadingSpinner},
        data() {
            return {
                processing: {
                    fetching: false,
                    inventory: false,
                    addingInventory: false,
                },
                product: null,
                inventories: [],
                form: {
                    from: '',
                    to: ''
                }
            }
        },
        computed: {
            productSlug() {
                return this.$route.params.slug;
            }
        },
        mounted() {
            this.startAfresh();
        },
        watch: {
            $route(to, from) {
                this.startAfresh();
            }
        },
        methods: {
            addToInventory() {
                document.getElementById('update-inventory-btn').click();
            },
            handleUpdateInventory(data) {
                this.inventories.unshift(data.data);
                this.product.quantity = data.total_quantity;
            },
            getInventory() {
                this.processing.inventory = true;
                this.inventories = [];
                const queryParam = [];

                if (this.form.from) {
                    queryParam.push(`from=${this.form.from}`)
                }
                if (this.form.to) {
                    queryParam.push(`to=${this.form.to}`)
                }
                const filterQuery = queryParam.length ? (`?${queryParam.join('&')}`) : '';

                axios.get(baseUrl + `products/${this.productSlug}/inventory${filterQuery}`).then(response => {
                    if (response.data && response.data.data) {
                        this.inventories = response.data.data || [];
                    }
                    this.processing.inventory = false;
                }).catch(error => {
                    this.processing.inventory = false;
                });
            },
            extractProperties(properties, json = false) {
                if (!properties) {
                    if (json) {
                        return [];
                    }
                    return ''
                }
                const property = [];
                if (json) {
                    return properties;
                } else {
                    properties.forEach(prop => {
                        property.push(`<strong class="">${prop.name}: </strong> <span class="text-primary">${prop.value}</span>`);
                    });

                    return property.join(' | ');
                }
            },
            startAfresh() {
                this.slug = this.$route.params.slug;
                this.loadDetail();
            },
            loadDetail() {
                this.processing.fetching = true;
                axios.get(baseUrl + `products/${this.productSlug}`).then(response => {
                    if (response.data && response.data.data) {
                        this.product = response.data.data;

                        this.inventories = this.product.inventory || [];
                    }
                    this.processing.fetching = false;
                }).catch(error => {
                    this.processing.fetching = false;
                });
            },
        }
    }
</script>

<style scoped>
    .has-bg-img {
        background-size: cover;
        background-position: center center;
        display: inline-block;
        width: 200px;
        height: 200px;
        max-width: 100%;
    }
</style>
