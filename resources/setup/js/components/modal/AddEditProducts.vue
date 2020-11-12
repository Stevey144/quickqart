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
                            <select class="custom-select w-100" @change="changeCategory()" v-model="form.category" required>
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
                <div class="deep-bg mb-3"
                     style="padding: 10px 10px 5px; background: #efefef; border-radius: 5px">
                    <div class="d-block">
                        <div class="">
                            <button type="button" @click="addVariant"
                                    class="except btn btn-sm btn-link px-0">Add variants
                            </button>
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
                <div class="deep-bg" style="padding: 10px 10px 5px; background: #ddd;">
                    <div class="d-block">
                        <div class="">
                            <button type="button" @click="addProperty" class="except btn btn-sm btn-link px-0">Add Specification
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-1" v-for="(property, i) in otherProperties">
                        <property-value v-bind:key="'spec' + i" v-model="otherProperties[i]"
                                        v-on:remove="value => removeSpec(i)"
                                        placeholder="Specification"
                                        :value="property"></property-value>
                    </div>
                    <div v-if="!otherProperties.length" class="form-group mb-1">
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
    import PropertyValue from "../../../../store/js/components/PropertyValue";

    export default {
        name: "AddEditProducts",
        components: {PropertyValue, Upload, Spinner},
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
                uploading: false,
                upload: null,
                uploadId: (Math.ceil(Math.random() * 10000000)),
            }
        },
        computed: {},
        methods: {
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
            removeImage(i) {
                toast.confirm('Confirmation', 'Do you want to remove the selected image?', (res) => {
                    if (res) {
                        this.form.image.splice(i, 1);
                    }
                });
            },
            addVariant() {
                this.variants.push({
                    name: '',
                    value: '',
                });
            },
            triggerUpload() {
                this.$root.$emit(eventName.triggerUpload, this.uploadId);
            },
            addCategory() {
                const form = {
                    ...this.form,
                    variants: this.variants,
                    properties: this.otherProperties
                };
                this.close(form);
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
                this.variants = this.product.variants || [];
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
