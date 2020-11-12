<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div><router-link to="/products" class="btn btn-warning btn-sm">Go back to product list</router-link></div>
                    <h4 class="m-0 mt-3 text-center">{{isEdit ? 'Edit product' : 'Add new product'}}</h4>
                </div>
            </div>
        </div>
        <div class="container page__container">
            <div class="row no-gutters">
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
                                            Submit the form, and your goods will be added to your list of products
                                        </li>
                                        <li>Remember, the better the price, the faster you sell.</li>
                                    </ul>
                                </div>
                                <div class="col-md-8 px-lg-5">
                                    <div  class="pt-3 mb-3"><h5  class="font-weight-bold" style="font-size: 20px !important;">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Category</label>
                                                <select class="form-control" @change="changeCategory()"
                                                        v-model="form.category" required>
                                                    <option v-for="category in categories" :value="category">
                                                        {{category.name}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Price</label>
                                                <input class="form-control" v-model.number="form.price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Initial Quantity</label>
                                                <input class="form-control" v-model.number="form.quantity" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">SKU</label>
                                                <input class="form-control" v-model="form.sku" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" v-model="form.description" required
                                                  placeholder="Enter short description"></textarea>
                                    </div>
                                    <div class="deep-bg" style="padding: 10px 10px 5px; background: #efefef; border-radius: 5px">
                                        <div class="d-block">
                                            <div class="">
                                                <button type="button" @click="addProperty"
                                                        class="except btn btn-sm btn-link px-0">Add Specification
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1" v-for="(property, i) in categoryProperties">
                                            <div class="input-group">
                                                <div class="col-md-6 p-0">
                                                    <input v-model="property.name" :readonly="true"
                                                           required placeholder="Property name"
                                                           class="form-control mr-lg-1">
                                                </div>
                                                <div class="col-md-6 p-0">
                                                    <div class="input-group input-group-merge">
                                                        <input v-if="!property.values.length" type="text"
                                                               class="form-control form-control-appended"
                                                               v-model="property.value"
                                                               placeholder="Property value">
                                                        <select v-else class="form-control form-control-appended"
                                                                v-model="property.value">
                                                            <option v-for="v in property.values.split(',')" :value="v">
                                                                {{v}}
                                                            </option>
                                                        </select>
                                                        <div class="input-group-append"
                                                             @click.prevent="categoryProperties.splice(i, 1)">
                                                            <div class="input-group-text">
                                                                <i class="material-icons">close</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1" v-for="(property, i) in otherProperties">
                                            <div class="input-group">
                                                <div class="col-md-6 p-0">
                                                    <input v-model="property.name" required placeholder="Property name"
                                                           class="form-control mr-lg-1">
                                                </div>
                                                <div class="col-md-6 p-0">
                                                    <div class="input-group input-group-merge">
                                                        <input v-if="!property.values || property.value.length"
                                                               type="text"
                                                               class="form-control form-control-appended"
                                                               v-model="property.value"
                                                               placeholder="Property value">
                                                        <select v-else class="form-control form-control-appended"
                                                                v-model="property.value">
                                                            <option
                                                                v-for="v in property.values.split(',').map(v => v.trim()).filter(v => !!v)"
                                                                :value="v">{{v}}
                                                            </option>
                                                        </select>
                                                        <div class="input-group-append"
                                                             @click.prevent="otherProperties.splice(i, 1)">
                                                            <div class="input-group-text">
                                                                <i class="material-icons">close</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                            <spinner v-else></spinner>
                                        </button>

                                    </div>
                                </div>
                            </div>
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
    import LoadingSpinner from "../components/LoadingSpinner";
    import toast from "../services/toast";
    import Upload from "../components/Upload";
    import Spinner from "../components/Spinner";

    export default {
        name: "AddEditSingleProduct",
        components: {Spinner, Upload, LoadingSpinner},
        data() {
            return {
                processing: {
                    fetching: false,
                    creating: false,
                },
                form: {
                    name: '',
                    price: '',
                    quantity: '',
                    sku: '',
                    description: '',
                    category: '',
                    images: [],
                    properties: []
                },
                isEdit: false,
                otherProperties: [],
                categoryProperties: [],
                uploading: false,
                upload: null,
                uploadId: (Math.ceil(Math.random() * 10000000)),
                categories: []
            }
        },
        computed: {
            isEdit() {
                return this.$route.params.slug !== 'new'
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

            }
        },

        methods: {
            startAfresh() {
                if (this.isEdit) {
                    this.loadData();
                }

                this.slug = this.$route.params.slug;
            },
            loadData() {
                this.processing.fetching = true;
                axios.get(`admin/products/${this.slug}`).then(response => {
                    if (response.data && response.data.data) {
                        this.form = {
                            ...this.form,
                            ...response.data.data
                        };
                    }
                    this.processing.fetching = false;
                }).catch(error => {
                    this.processing.fetching = false;
                });
            },
            removeImage(i) {
                toast.confirm('Confirmation', 'Do you want to remove the selected image?', (res) => {
                    if (res) {
                        this.form.image.splice(i, 1);
                    }
                });
            },
            triggerUpload() {
                this.$root.$emit(eventName.triggerUpload, this.uploadId);
            },
            changeCategory() {
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
        }
    }
</script>

<style lang="scss" scoped>
    .no-margin-left {
        &, & li {
            margin-left: -10px !important;
            margin-block-start: 0!important;
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
        position: absolute; right: 0; top: -7px;
        background: rgba(0, 0, 0, 0.8); border-radius: 50%; width: 23px; height: 25px;
        cursor: pointer;

        .material-icons {
            color: white;
        }
    }

    .border-right {
        border-color: #cfcfcf !important;
    }
</style>
