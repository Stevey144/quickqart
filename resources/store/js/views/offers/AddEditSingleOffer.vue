<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div>
                        <router-link to="/special-offers" class="btn btn-warning btn-sm">Go back to offers</router-link>
                    </div>
                    <h4 class="m-0 mt-3 text-center">{{isEdit ? 'Edit offer details' : 'Add new offer'}}</h4>
                </div>
            </div>
        </div>
        <div class="container page__container">
            <div v-if="processing.fetching" class="row">
                <div class="col-md-12">
                    <div class="text-center py-5 mt-5">
                        <loading-spinner></loading-spinner>
                    </div>
                </div>
            </div>
            <div v-else class="row  no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <form @submit.prevent="updateData()" class="col-md-12 px-lg-5">
                                    <div class="pt-3 mb-3"><h5 class="font-weight-bold"
                                                               style="font-size: 20px !important;">
                                        Enter the details of offer
                                    </h5></div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">Title</label>
                                                <input class="form-control" v-model="form.title" required
                                                       placeholder="Enter offer title">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Discount</label>
                                                <input class="form-control" v-model.number="form.discount" required>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <input class="form-control"
                                                        v-model="form.description" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Threshold</label>
                                                <input class="form-control" v-model.number="form.threshold" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Offers starts</label>
                                            <input class="form-control" v-model="form.start" required type="datetime-local">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Offers ends</label>
                                            <input class="form-control" v-model="form.end" required type="datetime-local">
                                        </div>
                                    </div>
                                    <div class=" mt-3"></div>
                                    <upload :trigger-id="uploadId"></upload>
                                    <div style="padding: 15px; background: #efefef; border-radius: 5px" class="deep-bg">
                                        <div class="">
                                            <div v-if="!form.banners.length" class="form-group mb-0">
                                                <i>No banners added yet...</i>
                                            </div>
                                            <div v-for="(image, i) in form.banners" class="d-inline-block mb-1 mr-1"
                                                 style="position: relative">
                                                <img :src="image" class="img-thumbnail" style="height: 100px;">
                                                <span @click.prevent="removeImage(i)" class="rm-img"><i
                                                    class="material-icons">close</i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-center mb-3">
                                        <button type="button" :disabled="uploading" @click.prevent="triggerUpload"
                                                class="btn btn-warning px-3 py-2 mr-3" style="min-width: 150px">
                                            <span>Add {{form.banners.length ? 'more' : ''}} image{{form.banners.length ? 's' : ''}}</span>
                                        </button>
                                        <button class="btn btn-primary px-3 py-2" style="min-width: 150px">
                                            <span v-if="!processing.creating">{{isEdit ? 'Update Record' : 'Create Record'}}</span>
                                            <spinner v-else></spinner>
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div v-if="!processing.fetching" class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="px-3 my-3 d-flex justify-content-between">
                            <h5>Products offered</h5>
                            <button @click.prevent="selectProduct()" :disabled="processing.loadingProducts"
                                    class="btn btn-sm btn-primary" style="min-width: 120px">
                                <spinner v-if="processing.loadingProducts"></spinner>
                                <span v-else>Add Product</span>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bproducted table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Name</th>
                                    <th>Price</th>
                                    <th style="min-width: 100px">Discount</th>
                                    <th style="min-width: 100px">New Price</th>
                                    <th style="min-width: 150px">Threshold</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(product, i) in offerProducts">
                                    <td>{{ (i + 1)}}</td>
                                    <td>
                                        <div v-if="product.is_featured"><span class="badge badge-soft-success">Featured</span></div>
                                        <router-link :to="`/products/${product.slug}/view`">
                                            {{product.product.name}}</router-link></td>
                                    <td>{{product.product.price | currency}}</td>
                                    <td>{{product.discount}}</td>
                                    <td>
                                        <span v-if="(product.discount || form.discount)">
                                            {{(product.product.price - ((product.discount || form.discount) * product.product.price / 100)) | currency}}
                                        </span>
                                    </td>
                                    <td>{{product.threshold}}</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button :disabled="processing" @click.prevent="deleteProduct(product, i)"
                                                        class="btn-link dropdown-item text-danger">Remove product
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!offerProducts.length">
                                    <td colspan="8" class="text-center">No products in list...</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LoadingSpinner from "../../components/LoadingSpinner";
    import toast from "../../services/toast";
    import Upload from "../../components/Upload";
    import Spinner from "../../components/Spinner";

    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        name: "AddEditSingleOffer",
        components: {Spinner, Upload, LoadingSpinner},
        data() {
            return {
                processing: {
                    fetching: false,
                    creating: false,
                    categories: false,
                    loadingProducts: false,
                },
                form: {
                    title: '',
                    description: '',
                    start: '',
                    end: '',
                    discount: '',
                    threshold: '',
                    banners: [],
                    offer_products: []
                },
                uploading: false,
                upload: null,
                uploadId: (Math.ceil(Math.random() * 10000000)),
                offer: null,
                offerProducts: [],
                allProducts: []
            }
        },
        computed: {
            isEdit() {
                return this.$route.params.slug !== 'new'
            },
            offerSlug() {
                return this.$route.params.slug;
            }
        },
        mounted() {
            this.startAfresh();

            this.$root.$on(eventName.uploadError, ({uploadId, value}) => {
                if (uploadId === this.uploadId) {
                    if (value) toast.error(value);
                    this.uploading = false;
                }
            });
            this.$root.$on(eventName.uploadDone, ({uploadId, value}) => {
                if (uploadId === this.uploadId) {
                    this.uploading = false;
                    this.form.banners.push(value.url);
                }
            });
            this.$root.$on(eventName.uploadProgress, ({uploadId, value}) => {
                if (uploadId === this.uploadId) {
                    this.uploading = value > 0;
                }
            });
        },

        watch: {
            $route(to, from) {
                this.startAfresh();
            }
        },

        methods: {
            selectProduct() {
                this.loadProducts().then(response => {
                    let options = ``;
                    this.allProducts.forEach(product => {
                        if (!this.offerProducts.find(p => p.product_id.toString() === product.id.toString())) {
                            options += `<option value="${product.slug}">${product.name} &mdash; SKU: ${product.sku}</option>`;
                        }
                    });
                    Swal.fire({
                        title: 'Add product',
                        html: `<small style="line-height: 1.5!important; margin-top: 15px">Select the product you wish to add, and specify the discount explicitly applicable to this product</small><div class="swal-content-content"><div><select class="filter-input" id="inputField1">${options}</select><input type="number" min="1" placeholder="Enter discount percent (1-100)" id="inputField2" class="filter-input"><input type="number" placeholder="Enter minimum quantity (Threshold) applicable" min="1" id="inputField3" class="filter-input"></div>` +
                            `<div class="error-area"></div></div>`,
                        preConfirm: () => {
                            return new Promise((resolve, reject) => {
                                const errorArea = $('.error-area');
                                errorArea.html('');
                                const inputField = $('#inputField1');
                                const inputField2 = $('#inputField2');
                                const inputField3 = $('#inputField3');
                                const data = {
                                    product: inputField.val().trim(),
                                    discount: inputField2.val().trim(),
                                    threshold: inputField3.val().trim(),
                                };

                                if (!data.product) {
                                    errorArea.html('<div style="margin-top: 15px; font-weight: bold; text-align: center">Select a product first!</div>');
                                    resolve(false);
                                } else {
                                    resolve(data);
                                }
                            })
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Add product',
                    }).then((result) => {
                        if (result.value) {
                            const product = this.allProducts.find(p => p.slug === result.value.product);

                            this.offerProducts.push({
                                product_id: product.id,
                                discount: result.value.discount || '',
                                threshold: result.value.threshold || '',
                                product: product
                            })
                        }
                    });
                });
            },
            loadProducts() {
                return new Promise((resolve, reject) => {
                    if (this.allProducts.length) {
                        resolve();
                        return;
                    }
                    this.processing.loadingProducts = true;
                    axios.get(baseUrl + `products?no-page=none`).then(response => {
                        if (response.data && response.data.data) {
                            this.allProducts = response.data.data;
                        }
                        this.processing.loadingProducts = false;
                        resolve();
                    }).catch(error => {
                        this.processing.loadingProducts = false;
                        reject();
                    });
                })

            },
            deleteProduct(product, i) {
                toast.confirm('Confirmation', 'Do you want to remove this product from list?', (value) => {
                    if (value) {
                        this.offerProducts.splice(i, 1);
                    }
                })
            },
            startAfresh() {
                if (this.isEdit) {
                    this.loadDetail();
                }

                this.slug = this.$route.params.slug;
            },

            loadDetail() {
                this.processing.fetching = true;
                axios.get(baseUrl + `special-offers/${this.offerSlug}`).then(response => {
                    if (response.data && response.data.data) {
                        this.offer = response.data.data;
                        this.form = {
                            ...this.form,
                            ...this.offer
                        };

                        this.offerProducts = this.offer.offer_products;
                    }
                    this.processing.fetching = false;
                }).catch(error => {
                    this.processing.fetching = false;
                });
            },
            updateData() {
                let request;
                const d = {
                    ...this.form,
                    offer_products: this.offerProducts.map(p => {
                        return {
                            product_id: p.product_id,
                            discount: p.discount,
                            threshold: p.threshold,
                        }
                    })
                }
                if (this.isEdit) {
                    request = axios.put(baseUrl + `special-offers/${this.slug}`, d);
                } else {
                    request = axios.post(baseUrl + `special-offers`, d);
                }
                this.processing.creating = true;
                request.then(response => {
                    if (response.data && response.data.data) {
                        toast.success(response.data.message);

                        if (!this.isEdit) {
                            this.form = {
                                title: '',
                                description: '',
                                start: '',
                                end: '',
                                discount: '',
                                threshold: '',
                                banners: [],
                                offer_products: []
                            };
                        }
                    }
                    this.processing.creating = false;
                }).catch(error => {
                    this.processing.creating = false;
                });
            },
            removeImage(i) {
                toast.confirm('Confirmation', 'Do you want to remove the selected image?', (res) => {
                    if (res) {
                        this.form.banners.splice(i, 1);
                    }
                });
            },
            triggerUpload() {
                this.$root.$emit(eventName.triggerUpload, this.uploadId);
            },
        }
    }
</script>

<style lang="scss" scoped>
    .no-margin-left {
        &, & li {
            margin-left: -10px !important;
            margin-block-start: 0 !important;
        }
    }

    .btn-light {
        background: #bfbebe;
    }

    .btn {
        &:not(.except) {
            border-radius: 0 !important;
            min-width: 100px;
        }
    }

    .rm-img {
        position: absolute;
        right: 0;
        top: -7px;
        background: rgba(0, 0, 0, 0.8);
        border-radius: 50%;
        width: 23px;
        height: 25px;
        cursor: pointer;

        .material-icons {
            color: white;
        }
    }

    .border-right {
        border-color: #cfcfcf !important;
    }
</style>
