<template>
    <div class="wrapper">
        <form @submit.prevent="addCategory()" class="form">
            <div class="m-header">
                <div class="d-flex justify-content-between">
                    <h4 class="header-text">{{isEdit ? 'Edit' : 'Add a'}} Product</h4>
                    <span @click.prevent="close()" style="cursor: pointer">
                    <i class="material-icons" style="font-size: 30px">close</i></span>
                </div>
            </div>

            <div class="m-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Product Name</label>
                            <input class="form-control" v-model="form.name" required placeholder="Enter product name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select class="form-control" @change="changeCategory()" v-model="form.category" required>
                                <option v-for="category in categories" :value="category">{{category.name}}</option>
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
                <div class="deep-bg" style="padding: 10px 10px 5px; background: #ddd;">
                    <div class="d-block">
                        <div class="">
                            <button type="button" @click="addProperty" class="except btn btn-sm btn-link px-0">Add Specification
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
                                        <option v-for="v in property.values.split(',')" :value="v">{{v}}</option>
                                    </select>
                                    <div class="input-group-append" @click.prevent="categoryProperties.splice(i, 1)">
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
                                    <input v-if="!property.values || property.value.length" type="text"
                                           class="form-control form-control-appended"
                                           v-model="property.value"
                                           placeholder="Property value">
                                    <select v-else class="form-control form-control-appended"
                                           v-model="property.value">
                                        <option v-for="v in property.values.split(',').map(v => v.trim()).filter(v => !!v)"
                                                :value="v">{{v}}</option>
                                    </select>
                                    <div class="input-group-append" @click.prevent="otherProperties.splice(i, 1)">
                                        <div class="input-group-text">
                                            <i class="material-icons">close</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="!otherProperties.length && !categoryProperties.length" class="form-group mb-1">
                        <i>No specifications yet...</i>
                    </div>
                </div>

                <div class=" mt-3"></div>
                <upload :trigger-id="uploadId"></upload>
                <div style="padding: 10px; background: #ddd;" class="deep-bg">
                    <div class="d-block">
                        <div class="">
                            <button type="button" @click="triggerUpload" class="btn btn-sm btn-link px-0 except">Add Image
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <div v-if="!form.images.length" class="form-group mb-1">
                            <i>No images added yet...</i>
                        </div>
                        <div v-for="(image, i) in form.images" class="d-inline-block mb-1 mr-1" style="position: relative">
                            <img :src="image" class="img-thumbnail" style="height: 100px;">
                            <span @click.prevent="removeImage(i)" class="rm-img"><i class="material-icons">close</i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-footer">
                <div class="d-block text-center">
                    <button type="submit" class="btn btn-primary mb-4 mt-3 py-2 px-3">
                        {{isEdit ? 'Update' : 'Add'}} Product
                    </button>
                    <button type="button" @click.prevent="close()" class="btn btn-light mb-4 mt-3 py-2">
                        Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import Spinner from "../Spinner";
    import Upload from "../Upload";
    import toast from "../../services/toast";

    export default {
        name: "AddEditProducts",
        components: {Upload, Spinner},
        props: ['product', 'categories'],
        data() {
            return {
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
            }
        },
        computed: {},
        methods: {
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
            close(data = false) {
                this.$emit('product-modal-close', data);
            }
        },
        mounted() {
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

            if (this.product) {
                this.isEdit = true;
                this.form = {...this.form, ...this.product};

                this.otherProperties = this.product.properties;
            }
        },
        beforeDestroy() {
            this.$root.$off(eventName.uploadError);
            this.$root.$off(eventName.uploadDone);
            this.$root.$off(eventName.uploadProgress);
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';

    .wrapper {
        display: flex;
        justify-content: center;
        max-width: 550px;
        box-shadow: 0 10px 25px 0 rgba(50, 50, 93, 0.07), 0 5px 15px 0 rgba(0, 0, 0, 0.07);
        border-radius: 5px;

        .form {
            width: 550px;
            background: $app-background;
        }

        .form-label {
            display: block;
        }

        .header-text {
            font-size: 22px;
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
</style>
