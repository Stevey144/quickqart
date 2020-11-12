<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div>
                        <router-link to="/products" class="btn btn-warning btn-sm">Go back to product list</router-link>
                    </div>
                    <h4 class="m-0 mt-3 text-center">{{isEdit ? 'Edit product details' : 'Add new product'}}</h4>
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
            <div v-else class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 pb-5 pt-3 px-lg-4 border-right"
                                     style="color: rgb(86,86,86); text-align: justify;">
                                    <h5 class="font-weight-bold mb-3" style="font-size: 20px">Instruction</h5>
                                    <ul class="no-margin-left">
                                        <li>Enter your
                                            product's name and select the category it belongs to.
                                        </li>
                                        <li>
                                            Enter the price (per unit) in the price box, and the quantity
                                            available in the quantity box.
                                        </li>
                                        <li>
                                            Complete the process by entering the description specific to your
                                            product, add specifications and upload up to five different images
                                            for your product.
                                        </li>
                                        <li>
                                            <strong>For every specification that can make a product different
                                                (unacceptable when delivered after order has been placed), e.g. same
                                                products with different sizes, we recommend you to create them as
                                                separate products with the sizes as suffix, this will make each product
                                                distinct and less confusing for your customers when they place their
                                                orders.</strong>
                                        </li>

                                        <li>
                                            Submit the form, and your goods will be added to your list of products
                                        </li>
                                        <li>Remember, the better the price, the faster you sell.</li>
                                    </ul>
                                </div>
                                <form @submit.prevent="updateData()" class="col-md-8 px-lg-5">
                                    <div class="pt-3 mb-3"><h5 class="font-weight-bold"
                                                               style="font-size: 20px !important;">
                                        Enter the details of your
                                        product
                                    </h5></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Product Name</label>
                                                <input class="form-control" v-model="form.name" required
                                                       placeholder="Enter product name">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Category</label>
                                                <span class="help-tip">
                                                    <div class="help-tip-content">Product categories are used to organise your product list. This will make it easier for buyers to find products.</div>
                                                </span>
                                                <div class="input-group">
                                                    <select class="custom-select w-100" @change="changeCategory()"
                                                            v-model="form.category" required>
                                                        <option value="new">Add a new category</option>
                                                        <option v-for="category in categories" :value="category">
                                                            {{category.name}}
                                                        </option>
                                                    </select>
                                                </div>
                                                <span v-if="!categories.length && !processing.categories"
                                                      @click.prevent="loadCategories()" class="fake-link pointer">Reload categories</span>
                                                <span v-if="processing.categories" class="fake-link">Fetching categories...</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Price</label>
                                                <input class="form-control" v-model.number="form.price" required>
                                            </div>
                                        </div>
                                        <div v-if="!isEdit" class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Initial Quantity</label>
                                                <span class="help-tip">
                                                    <div class="help-tip-content">The current number units you have available for this product currently</div>
                                                </span>
                                                <input class="form-control" v-model.number="form.quantity" required>
                                            </div>
                                        </div>
                                        <div :class="{'col-md-16': isEdit, 'col-md-12': !isEdit}">
                                            <div class="form-group">
                                                <label class="form-label">SKU</label>
                                                <span class="help-tip">
                                                    <div class="help-tip-content">Store Keeping Unit (SKU) is the products's identifier which can be used to uniquely identify this product.</div>
                                                </span>
                                                <input class="form-control" v-model="form.sku" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" v-model="form.description" rows="6" required
                                                  placeholder="Enter short description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Main Features</label>
                                        <span class="help-tip">
                                            <div class="help-tip-content">Highlight the main features of this product, each one separated from the other by a new line</div>
                                        </span>
                                        <textarea class="form-control" v-model="form.features"
                                                  placeholder="Highlight product's main features"></textarea>
                                    </div>
                                    <div class="deep-bg mb-3"
                                         style="padding: 10px 10px 5px; background: #efefef; border-radius: 5px">
                                        <div class="d-block">
                                            <div class="">
                                                <button type="button" @click="addVariant"
                                                        class="except btn btn-sm btn-link px-0">Add variants
                                                </button>
                                                <span class="help-tip">
                                                    <div class="help-tip-content">Click to specify the many variants/options available for this product</div>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1" v-for="(variant, i) in variants">
                                            <property-value v-bind:key="'variant' + i" placeholder="Variant"
                                                            v-on:remove="value => removeVariant(i)"
                                                            :value="variant" v-model="variants[i]"></property-value>
                                        </div>
                                        <div v-if="!variants.length"
                                             class="form-group mb-1">
                                            <i>No variants specified yet...</i>
                                        </div>
                                    </div>

                                    <div class="deep-bg"
                                         style="padding: 10px 10px 5px; background: #efefef; border-radius: 5px">
                                        <div class="d-block">
                                            <div class="">
                                                <button type="button" @click="addProperty"
                                                        class="except btn btn-sm btn-link px-0">Add Specification
                                                </button>
                                                <span class="help-tip">
                                                    <div class="help-tip-content">Click to highlight the specifications as applied to this product</div>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1" v-for="(property, i) in otherProperties">
                                            <property-value v-bind:key="'spec' + i" v-model="otherProperties[i]"
                                                            v-on:remove="value => removeSpec(i)"
                                                            placeholder="Specification"
                                                            :value="property"></property-value>
                                        </div>
                                        <div v-if="!otherProperties.length && !categoryProperties.length"
                                             class="form-group mb-1">
                                            <i>No specifications yet...</i>
                                        </div>
                                    </div>

                                    <div class=" mt-3"></div>
                                    <upload :trigger-id="uploadId"></upload>
                                    <div style="padding: 15px; background: #efefef; border-radius: 5px" class="deep-bg">
                                        <div class="">
                                            <div v-if="!form.images.length" class="form-group mb-0">
                                                <i>No images added yet...</i>
                                            </div>
                                            <div v-for="(image, i) in form.images" class="d-inline-block mb-1 mr-1"
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
                                            <span>Add {{form.images.length ? 'more' : ''}} image{{form.images.length ? 's' : ''}}</span>
                                        </button>
                                        <button class="btn btn-primary px-3 py-2" style="min-width: 150px">
                                            <span v-if="!processing.creating">{{isEdit ? 'Update Record' : 'Create Record'}}</span>
                                            <spinner :stroke-color="'#ffffff'" v-else></spinner>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <transition name="modal">
            <div v-if="selectedModal" class="bee-modal">
                <div class="overlay">
                    <add-edit-product-category v-on:category-modal-close="handleModalClosed($event)"
                                               :category="selectedModal.data"></add-edit-product-category>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import LoadingSpinner from "../../components/LoadingSpinner";
    import toast from "../../services/toast";
    import Upload from "../../components/Upload";
    import Spinner from "../../components/Spinner";
    import PropertyValue from "../../components/PropertyValue";
    import AddEditProductCategory from "../../../../store/js/components/modal/AddEditProductCategory";

    export default {
        name: "AddEditSingleProduct",
        components: {AddEditProductCategory, PropertyValue, Spinner, Upload, LoadingSpinner},
        data() {
            return {
                selectedModal: null,
                processing: {
                    fetching: false,
                    creating: false,
                    categories: false,
                },
                form: {
                    name: '',
                    price: '',
                    quantity: '',
                    sku: '',
                    description: '',
                    features: '',
                    category: '',
                    images: [],
                    properties: [],
                },
                otherProperties: [],
                categoryProperties: [],
                uploading: false,
                upload: null,
                uploadId: (Math.ceil(Math.random() * 10000000)),
                categories: [],
                variants: [
                    {
                        name: 'Color',
                        value: "Red,Yellow,Green,Blue,White,Black"
                    },
                    {
                        name: 'Size',
                        value: "S,M,L,XL,XXL"
                    }
                ],
                product: null
            }
        },
        computed: {
            isEdit() {
                return this.$route.params.slug !== 'new'
            },
            productSlug() {
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
                    this.form.images.push(value.url);
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
            handleModalClosed(data) {
                if (data) {
                    this.categories.push(data);
                    this.form.category = data;
                }

                this.selectedModal = null;
            },

            removeSpec(index) {
                const newArray = [
                    ...this.otherProperties
                ];
                newArray.splice(index, 1);
                this.otherProperties = [];
                setTimeout(() => {
                    this.otherProperties = [...newArray]
                });
            },
            removeVariant(index) {
                const newArray = [
                    ...this.variants
                ];

                newArray.splice(index, 1);
                this.variants = [];
                setTimeout(() => {
                    this.variants = [...newArray]
                });
            },
            startAfresh() {
                if (this.isEdit) {
                    this.loadDetail();
                } else {
                    this.loadCategories();
                }

                this.slug = this.$route.params.slug;
            },

            loadCategories() {
                if (this.processing.categories || this.categories.length) {
                    return;
                }
                this.processing.categories = true;
                axios.get(baseUrl + `categories?pagination=none`).then(response => {
                    if (response.data && response.data.data) {
                        this.categories = response.data.data
                    }
                    this.processing.categories = false;
                }).catch(error => {
                    this.processing.categories = false;
                });
            },
            loadDetail() {
                this.processing.fetching = true;
                axios.get(baseUrl + `products/${this.productSlug}`).then(response => {
                    if (response.data && response.data.data) {
                        this.product = response.data.data;
                        this.form = {
                            ...this.product,
                            category: this.product.category,
                            features: this.product.main_features || ''
                        };

                        this.otherProperties = [
                            ...this.product.properties
                        ];
                        this.variants = this.product.variants || [];


                        this.loadCategories();
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
                    category_id: this.form.category ? this.form.category.id : null,
                    properties: [
                        ...this.categoryProperties,
                        ...this.otherProperties,
                    ],
                    variants: [...this.variants]
                };
                if (this.isEdit) {
                    request = axios.put(baseUrl + `products/${this.slug}`, d);
                } else {
                    request = axios.post(baseUrl + `products`, d);
                }
                this.processing.creating = true;
                request.then(response => {
                    if (response.data && response.data.data) {
                        toast.success(response.data.message);

                        if (!this.isEdit) {
                            this.form = {
                                name: '',
                                price: '',
                                quantity: '',
                                sku: '',
                                description: '',
                                features: '',
                                category: '',
                                images: [],
                                properties: []
                            };

                            this.variants = [];
                            this.otherProperties = [];
                            this.categoryProperties = [];
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
                        this.form.images.splice(i, 1);
                    }
                });
            },
            triggerUpload() {
                this.$root.$emit(eventName.triggerUpload, this.uploadId);
            },
            changeCategory() {
                if (this.form.category && this.form.category === 'new') {
                    this.form.category = '';
                    //show create modal
                    this.selectedModal = {
                        data: false
                    };
                }

                const category = this.form.category || [];

                if (category) {
                    this.categoryProperties = category.properties.map(c => {
                        return {
                            value: '',
                            ...c,
                        }
                    });
                } else {
                    this.categoryProperties = [];
                }
            },
            addCategory() {
                this.close(this.form);
            },
            addProperty() {
                this.otherProperties.push({
                    name: '',
                    value: '',
                    values: ''
                });
            },
            addVariant() {
                this.variants.push({
                    name: '',
                    value: '',
                });
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
