<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__container mt-4">
            <div v-if="!processData.fetching && order" class="row no-gutters">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="card-header__title text-center text-lg-left">
                                        <span style="margin-bottom: 15px; display: inline-block">Order Details</span>

                                        <span style="" v-if="order.contact && order.contact.comment" class="help-tip">
                                            <div class="help-tip-content">{{order.contact.comment}}</div>
                                        </span>
                                    </h4>
                                </div>
                                <div class="col-md-6 mt-2 mt-md-0 text-center text-md-right">
                                    <div v-if="order.requires_action" class="dropdown ml-auto">
                                        <button href="#" class="btn btn-primary dropdown-toggle"
                                                data-caret="false" :disabled="processing"
                                                data-toggle="dropdown">
                                            Change order status <i class="ml-3 material-icons">more_vert</i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button v-if="order.can_cancel" :disabled="processing"
                                                    @click.prevent="cancelOrder()"
                                                    class="btn-link dropdown-item text-danger">Cancel Order
                                            </button>
                                            <button v-if="order.can_pay" :disabled="processing"
                                                    @click.prevent="updateStatus('paid')"
                                                    class="btn-link dropdown-item text-primary">Mark as paid
                                            </button>
                                            <button v-if="order.can_be_delivered" :disabled="processing"
                                                    @click.prevent="updateStatus('delivered')"
                                                    class="btn-link dropdown-item text-success">Mark as delivered
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Modified</th>
                                    </tr>
                                    </thead>
                                    <tbody class=" text-primary">
                                    <tr>
                                        <td style="min-width: 120px">#{{order.slug}}</td>
                                        <td style="min-width: 120px">
                                            <div v-if="order.contact">
                                                {{order.contact.firstName + ' ' + order.contact.lastName}}
                                            </div>
                                        </td>
                                        <td>
                                            <div v-if="order.contact">
                                                <a :href="'tel:' + order.contact.phone" class="mr-2">{{order.contact.phone}}</a>
                                                <a v-if="order.contact" :href="'mailto:' + order.contact.email">{{order.contact.email}}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div v-if="order.contact">{{order.contact.address1 + ' ' +
                                                order.contact.address2}}, {{order.contact.city}}
                                            </div>
                                            <div v-if="order.contact">{{order.contact.state}},
                                                {{order.contact.country}}
                                            </div>
                                        </td>
                                        <td style="min-width: 120px">
                                            <span :class="'badge badge-' + order.status_color">{{order.status_description}}</span>
                                        </td>
                                        <td style="min-width: 120px">{{order.created_at | timeago}}</td>
                                        <td style="min-width: 120px">{{order.updated_at | timeago}}</td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!processData.fetching && order" class="row no-gutters">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white">
                            <h4 class="card-header__title">Items purchased</h4>
                        </div>
                        <div class="">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class=" text-primary">
                                    <tr>
                                        <th class="except-sm" style="width: 40px">#</th>
                                        <th style="min-width: 120px">Image</th>
                                        <th style="min-width: 120px">Item</th>
                                        <th style="min-width: 120px">Price</th>
                                        <th style="min-width: 120px">Quantity</th>
                                        <th style="min-width: 120px">Total</th>
                                        <th v-if="order.can_cancel" style="min-width: 40px">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, i) in (order.order_items || [])">
                                        <td>{{ (i + 1)}}</td>
                                        <td>
                                            <img style="width: 50px" v-if="item.product" :src="item.product.cover">
                                        </td>
                                        <td>
                                            <div v-if="item.product">
                                                <span>{{item.product.name}}</span>
                                                <div v-if="item.options && item.options.length">
                                                    <small v-for="(option, i) in item.options ">
                                                        <small v-if="i !== 0"> / </small>
                                                        {{option.name}}: {{option.value}}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{item.price | currency}}</td>
                                        <td>{{item.quantity}}</td>
                                        <td>{{item.amount | currency}}</td>
                                        <td v-if="order.can_cancel">
                                            <div class="dropdown ml-auto">
                                                <a href="#" class="dropdown-toggle text-muted"
                                                   data-caret="false"
                                                   data-toggle="dropdown">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <button :disabled="processing"
                                                            @click.prevent="updateQuantity(item, i)"
                                                            class="btn-link dropdown-item text-primary">Update Quantity
                                                    </button>
                                                    <button :disabled="processing" @click.prevent="removeItem(item, i)"
                                                            class="btn-link dropdown-item text-danger">Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!(order.order_items || []).length">
                                        <td colspan="7" class="text-center">
                                            No item found in order
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="5">Total</th>
                                        <td colspan="2">{{order.amount | currency}}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="processData.fetching" class="row no-gutters">
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
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    import toast from "../../services/toast";

    export default {
        name: "SingleOrder",
        components: {Spinner, LoadingSpinner},
        data() {
            return {
                processData: {
                    fetching: false,
                    quantity: false,
                    removingItem: false,
                    cancelling: false,
                    status: false,
                },
                order: null,
                form: {
                    from: '',
                    to: ''
                },
            }
        },
        computed: {
            orderId() {
                return this.$route.params.orderId;
            },
            processing() {
                return this.processData.cancelling || this.processData.fetching
                    || this.processData.quantity || this.processData.removingItem || this.processData.status;
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
            updateQuantity(item, i) {
                if (this.processing) {
                    return;
                }
                Swal.fire({
                    title: 'Update quantity',
                    html: `<small style="line-height: 1.5!important; margin-top: 15px">Enter the new quantity. Only positive numbers are allowed.</small><div class="swal-content-content"><div><input value="${item.quantity}" type="number" placeholder="Enter quantity greater than 0" id="inputField" class="filter-input"></div>` +
                        `<div class="error-area"></div></div>`,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            const errorArea = $('.error-area');
                            errorArea.html('');
                            const inputField = $('#inputField');
                            const data = {
                                value: inputField.val().trim(),
                            };

                            const quantity = Math.abs(parseInt(data.value.trim()));
                            inputField.val(quantity);

                            if (!quantity) {
                                errorArea.html('<div style="margin-top: 15px; font-weight: bold; text-align: center">Enter a valid quantity!</div>');
                                resolve(false);
                            } else {
                                resolve({quantity});
                            }
                        })
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                }).then((result) => {
                    if (result.value) {
                        this.processData.quantity = true;
                        axios.put(`${baseUrl}orders/${this.orderId}/order-items/${item.slug}`, {quantity: result.value.quantity}).then(response => {
                            if (response.data.data) {
                                this.order = response.data.data;
                            }
                            this.processData.quantity = false;
                        }).catch(error => {
                            this.processData.quantity = false;
                        });
                    }
                });
            },
            removeItem(item, i) {
                toast.confirm('Confirmation', 'Do you want to remove this item completely from the order list?', (value) => {
                    if (value) {
                        this.processData.removingItem = true;
                        axios.delete(`${baseUrl}orders/${this.orderId}/order-items/${item.slug}`).then(response => {
                            if (response.data.data) {
                                this.order = response.data.data;
                            }
                            this.processData.removingItem = false;
                        }).catch(error => {
                            this.processData.removingItem = false;
                        });
                    }
                });
            },
            cancelOrder() {
                if (this.processing) {
                    return;
                }
                toast.confirm('Confirmation', 'Do you want to cancel this order?', (value) => {
                    if (value) {
                        this.processData.cancelling = true;
                        axios.delete(`${baseUrl}orders/${this.orderId}`).then(response => {
                            if (response.data.data) {
                                this.order = response.data.data;
                            }
                            this.processData.cancelling = false;
                        }).catch(error => {
                            this.processData.cancelling = false;
                        });
                    }
                });
            },
            updateStatus(status) {
                if (this.processing) {
                    return;
                }
                toast.confirm('Confirmation', `Do you want to change this order status to ${status}?`, (value) => {
                    if (value) {
                        this.processData.status = true;
                        axios.put(`${baseUrl}orders/${this.orderId}/status`, {status}).then(response => {
                            if (response.data.data) {
                                this.order = response.data.data;
                            }
                            this.processData.status = false;
                        }).catch(error => {
                            this.processData.status = false;
                        });
                    }
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
                this.processData.fetching = true;
                axios.get(baseUrl + `orders/${this.orderId}`).then(response => {
                    if (response.data && response.data.data) {
                        this.order = response.data.data;
                    }
                    this.processData.fetching = false;
                }).catch(error => {
                    this.processData.fetching = false;
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

    .table {
        margin-bottom: 0 !important;
    }
</style>
