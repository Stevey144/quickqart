<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading">
                <div class="mt-3 mb-3">
                    <h4 v-if="!storeLink" class="header-heading font-weight-bold m-0 text-center">{{steps[currentStep]}}</h4>
                    <h4 v-else class="header-heading font-weight-bold m-0 text-center">Your store is ready</h4>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="text-center progress-tab-container" style="position: relative;">
                        <div
                            style="position: absolute; width: 350px; height: 5px; background: #ededed; top: 34px; display: flex">
                            <span v-for="(step, i) in steps" class="progress-tab-line" v-if="i < 3"
                                  :class="{'active' : currentStep > i}"></span>
                        </div>
                        <span v-for="(step, i) in steps" class="progress-tab-item"
                              :class="{'active' : currentStep >= i}">{{(i + 1)}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="form" class="container page__container">
            <div class="row d-flex justify-content-center">
                <div v-if="!storeLink" class="col-md-3">
                    <div class="card">
                        <div class="border-bottom">

                            <div v-if="form" class="card-body text-center">
                                <div class="mb-3">
                                    <img v-if="form.logo" :src="form.logo" alt="Logo"
                                         width="100" class="rounded-circle">
                                    <span v-else class="avatar mr-2"
                                          style="width: 6rem!important; height: 6rem!important; font-size: 2rem!important;">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-muted"
                                              style="color: #1377C9!important;">{{authUser.name.substr(0, 1)}}</span>
                                    </span>
                                </div>
                                <p class="font-weight-bold"> {{authUser.name}}</p>
                                <div>
                                    <span v-if="form.slug">Store ID: {{form.slug}} <br><br></span>
                                    <button type="button" @click.prevent="chooseID()"
                                            class="btn btn-sm btn-outline-success btn-sm px-3 py-1"
                                            style="border-radius: 0">{{form.slug ? 'Change Store' : 'Choose an'}} ID
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0 mb-4">
                            <ul class="list-unstyled list-skills">
                                <li class="file-list-item mb-1">
                                    <a target="_blank" href="" @click.prevent="triggerUpload('logo')">
                                        {{form.logo ? 'Change' : 'Upload a'}} Logo</a>
                                </li>
                                <li class="file-list-item mb-1">
                                    <a target="_blank" href="" @click.prevent="triggerUpload('mobile_logo')">
                                        {{form.mobile_logo ? 'Change ' : 'Upload a '}} mobile logo</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div v-if="!storeLink" class="col-md-8">
                    <div class="row">
                        <div v-if="form" class="col-12">
                            <upload :trigger-id="uploadId"></upload>
                            <div class="card card-form">
                                <div class="row no-gutters">
                                    <div class="col-lg-12 card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p><strong class="headings-color">{{steps[currentStep]}}</strong></p>
                                                <div>Ensure all details provided are valid and verifiable</div>
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-outline-info btn-sm" type="button"
                                                        v-if="currentStep === 1"
                                                        @click.prevent="openCreateModal(false, null, 'category')">
                                                    Add Category
                                                </button>
                                                <button class="btn btn-outline-info btn-sm" type="button"
                                                        v-if="currentStep === 2"
                                                        @click.prevent="openCreateModal(false, null, 'product')">
                                                    Add Product
                                                </button>

                                            </div>
                                        </div>

                                    </div>
                                    <form v-if="form" @submit.prevent="doSubmit()"
                                          class="col-lg-12 card-form__body card-body">
                                        <div v-if="currentStep === 0" class="row fadeInUp">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="fname">Business Name</label>
                                                            <input id="fname" type="text" name="name"
                                                                   v-model="form.name" class="form-control"
                                                                   placeholder="Business Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Email Address (optional)</label>
                                                            <input id="email" type="email" name="email"
                                                                   v-model="form.contact.email" class="form-control"
                                                                   placeholder="Business Email address">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Phone Number (optional)</label>
                                                            <input id="phone" type="text" name="phone_number"
                                                                   v-model="form.contact.phone_number"
                                                                   class="form-control"
                                                                   placeholder="Business Hot Line">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">Location (optional)</label><br/>
                                                            <input id="location" class="form-control"
                                                                   name="state" placeholder="Location address"
                                                                   v-model="form.address.address"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">State/Province</label><br/>
                                                            <input id="state" class="form-control"
                                                                   name="state" placeholder="State of location"
                                                                   v-model="form.address.state"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lga">Country</label><br/>
                                                            <select id="lga" required class="custom-select w-100"
                                                                    name="state"
                                                                    v-model="form.address.country">
                                                                <option v-for="country in countries" :value="country">
                                                                    {{country}}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Currency</label>
                                                        <select required class="custom-select w-100"
                                                                name="state" v-model="form.currency">
                                                            <option v-for="currency in currencies" :value="currency">
                                                                {{currency.name}} ({{currency.code}})
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="desc">Description</label>
                                                    <textarea v-model="form.description" required id="desc" rows="7"
                                                              class="form-control"
                                                              placeholder="Write briefly about your store"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentStep === 1" class="row fadeInUp">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead class=" text-primary">
                                                        <tr>
                                                            <th class="except-sm" style="width: 40px">#</th>
                                                            <th>Category</th>
<!--                                                            <th>Properties</th>-->
                                                            <th class="action-header">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(category, i) in (form.categories || [])">
                                                            <td>{{ (i + 1)}}</td>
                                                            <td style="min-width: 300px" class="text-capitalize">
                                                                {{category.name}}
                                                            </td>
<!--                                                            <td>-->
<!--                                                                {{category.properties.map(p => p.name).join(', ')}}-->
<!--                                                            </td>-->
                                                            <td>
                                                                <div class="dropdown ml-auto">
                                                                    <a href="#" class="dropdown-toggle text-muted"
                                                                       data-caret="false"
                                                                       data-toggle="dropdown">
                                                                        <i class="material-icons">more_vert</i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a href="#"
                                                                           @click.prevent="openCreateModal(category, i, 'category')"
                                                                           class="btn-link dropdown-item text-primary">Edit
                                                                        </a>
                                                                        <a href="#"
                                                                           @click.prevent="form.categories.splice(i, 1)"
                                                                           class="btn-link dropdown-item text-danger">Remove
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="!(form.categories || []).length">
                                                            <td colspan="4" class="text-center">No categories added
                                                                yet...
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentStep === 2" class="row fadeInUp">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead class=" text-primary">
                                                        <tr>
                                                            <th class="except-sm" style="width: 40px">#</th>
                                                            <th>Product</th>
                                                            <th>Category</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                            <th class="action-header">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(product, i) in (form.products || [])">
                                                            <td>{{ (i + 1)}}</td>
                                                            <td style="min-width: 150px" class="text-capitalize">
                                                                {{product.name}}
                                                            </td>
                                                            <td>
                                                                {{product.category.name}}
                                                            </td>
                                                            <td>
                                                                {{product.price | currency}}
                                                            </td>
                                                            <td>
                                                                {{product.quantity}}
                                                            </td>
                                                            <td>
                                                                <div class="dropdown ml-auto">
                                                                    <a href="#" class="dropdown-toggle text-muted"
                                                                       data-caret="false"
                                                                       data-toggle="dropdown">
                                                                        <i class="material-icons">more_vert</i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a href="#"
                                                                           @click.prevent="openCreateModal(product, i, 'product')"
                                                                           class="btn-link dropdown-item text-primary">Edit
                                                                        </a>
                                                                        <a href="#"
                                                                           @click.prevent="form.products.splice(i, 1)"
                                                                           class="btn-link dropdown-item text-danger">Remove
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr v-if="!(form.categories || []).length">
                                                            <td colspan="6" class="text-center">No product added
                                                                yet...
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentStep === 3" class="row fadeInUp">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <td>Store ID</td>
                                                            <td style="min-width: 150px">
                                                                <div v-if="form.slug">
                                                                    Your shop will be accessible at<a
                                                                    :href="'https://' + form.slug + '.quickqart.com'">
                                                                    https://{{form.slug}}.quickqart.com</a>
                                                                </div>
                                                                <button v-else type="button"
                                                                        class="btn btn-outline-primary btn-sm px-3 py-1"
                                                                        style="border-radius: 0"
                                                                        @click.prevent="chooseID">Choose an ID
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Logo</td>
                                                            <td style="min-width: 150px">
                                                                <img v-if="form.logo" style="height: 80px;" class="img-thumbnail" :src="form.logo">
                                                                <a v-else href="#" @click.prevent="triggerUpload('logo')">Click to upload a logo</a>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>Contact details</td>
                                                            <td style="min-width: 150px">
                                                                <i class="fa fa-envelope mr-2"></i> <a
                                                                :href="'email:' + form.contact.email">{{form.contact.email || 'NA'}}</a><br>
                                                                <i class="fa fa-phone mr-2"></i> <a
                                                                :href="'tel:' + form.contact.phone_number">{{form.contact.phone_number || 'NA'}}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Location</td>
                                                            <td style="min-width: 150px">
                                                                <i class="fa fa-map-marker mr-2"></i>
                                                                <span>{{[form.address.address, form.address.state, form.address.country].join(', ') || 'NA'}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Other Details</td>
                                                            <td style="min-width: 150px">
                                                                <span class="badge badge-primary">{{form.categories.length}} categories</span>
                                                                <span class="badge badge-success">{{form.products.length}} products</span>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <input style="display: none" type="file" id="file-picker" name="file">
                                        <input style="display: none" type="file" id="id-file-picker"
                                               name="identity_card">

                                        <div class="row">
                                            <div class="col-12 mb-5 mt-4 text-center custom-btn">
                                                <div class="d-flex justify-content-center"></div>
                                                <button :disabled="currentStep < 1 || processing" type="button"
                                                        @click="currentStep--"
                                                        style="min-width: 120px" class="btn btn-outline-primary">
                                                    <span>
                                                         <i class="fa fa-chevron-left"></i> <span>&nbsp; Go back</span>
                                                    </span>
                                                </button>
                                                <button v-if="currentStep < 3" :disabled="currentStep > 3 || processing"
                                                        type="submit"
                                                        style="min-width: 120px" class="btn btn-outline-primary">
                                                    <span>
                                                        <span>Next &nbsp;</span> <i class="fa fa-chevron-right"></i>
                                                    </span>
                                                </button>
                                                <button v-else :disabled="processing" type="submit"
                                                        style="min-width: 120px" class="btn btn-primary">
                                                    <span>
                                                        <span>Complete Setup &nbsp;</span> <i
                                                        class="fa fa-check-circle"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-form">
                                <div class="row no-gutters">
                                    <div class="col-lg-12 card-body">
                                        <div class="text-center">
                                            <div>
                                                <div class="d-inline-block text-center" style="box-shadow: 0 10px 25px 0 rgba(50, 50, 93, 0.07), 0 5px 15px 0 rgba(0, 0, 0, 0.07); width: 75px; height: 75px;  background: wheat; border-radius: 50%">
                                                    <span style="font-size: 60px; line-height: 75px; margin-left: 5px" class="material-icons mr-2 text-white">check</span>
                                                </div>
                                                <h4 class="text-center mt-3">
                                                    <strong class="headings-color text-success text-center">
                                                        Setup Completed</strong></h4>

                                                <div>
                                                    You store has been setup successfully.
                                                </div>

                                                <div class="text-center mt-3 mb-3">
                                                    <a :href="storeLink" class="btn btn-outline-primary px-3 py-2">Manage your products</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <transition name="modal">
            <div v-if="selectedModal.type" class="bee-modal">
                <div class="overlay">
                    <add-edit-product-category v-if="selectedModal.type === 'category'"
                                               v-on:category-modal-close="handleModalClosed($event)"
                                               :category="selectedModal.item"></add-edit-product-category>
                    <add-edit-products v-if="selectedModal.type === 'product'"
                                       v-on:product-modal-close="handleModalClosed($event)"
                                       :product="selectedModal.item" :categories="form.categories"></add-edit-products>

                </div>
            </div>
        </transition>
    </div>

</template>

<script>
    import {getAuthUser} from "../init-app";
    import toast from "../services/toast";
    import Spinner from "../components/Spinner";
    import Upload from "../components/Upload";
    import AddEditProductCategory from "../components/modal/AddEditProductCategory";
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    import AddEditProducts from "../components/modal/AddEditProducts";
    import {currencyList} from "../../../constants";

    export default {
        name: "Dashboard",
        components: {AddEditProducts, AddEditProductCategory, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processing: false,
                canChangeRole: false,
                roles: [],
                uploading: false,
                upload: null,
                uploadId: (Math.ceil(Math.random() * 10000000)),
                steps: [
                    'Setup Store details',
                    'Create Product Categories',
                    'Add Product Records',
                    'Complete setup'
                ],
                currentStep: 0,
                uploadType: null,
                countries: window.countryList.split('|'),
                selectedModal: {
                    item: null,
                    type: null,
                    index: null
                },
                storeLink: null,
                storageID: 'qq_ss_d',
                currencies: currencyList
            }
        },
        mounted() {
            localStorage.getItem(this.storageID);
            this.authUser = getAuthUser();
            this.resetFields();

            this.$root.$on(eventName.uploadError, ({uploadId, value}) => {
                if (uploadId === this.uploadId) {
                    if (value) toast.error(value);
                    this.uploading = false;
                }
            });
            this.$root.$on(eventName.uploadDone, ({uploadId, value}) => {
                if (uploadId === this.uploadId) {
                    this.uploading = false;
                    this.upload = value;

                    if (this.uploadType === 'mobile_logo') {
                        this.form.mobile_logo = value.url;
                    } else {
                        this.form.logo = value.url;
                    }
                }
            });
            this.$root.$on(eventName.uploadProgress, ({uploadId, value}) => {
                if (uploadId === this.uploadId) {
                    this.uploading = value > 0;
                }
            });

        },
        methods: {
            chooseID() {
                Swal.fire({
                    title: this.form.slug ? 'Change Store ID' : 'Choose a Store ID',
                    html: `<small style="line-height: 1.5!important; margin-top: 15px">Note: store ID can be a combination of both number and alphabet, with each word separated by an hyphen</small><div class="swal-content-content text-left"><div style="position: relative;"><input placeholder="Enter your ID" value="" id="inputField" value="${this.form.slug}" class="filter-input appended-text-tail" style="width: calc(100% - 120px)!important;"><span class="append-text-tail" style="position: absolute;right: 10px;top: 33px;">.quickqart.com</span></div></div>` +
                        `<div class="error-area"></div></div>`,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            const errorArea = $('.error-area');
                            errorArea.html('');
                            const inputField = $('#inputField');
                            const data = {
                                storeId: inputField.val().trim(),
                            };

                            const storeId = this.getSlug(data.storeId.trim());
                            inputField.val(storeId);

                            if (!storeId) {
                                errorArea.html('<div style="margin-top: 15px; font-weight: bold; text-align: center">Enter a valid ID for your store!</div>');
                                resolve(false);
                            } else {

                                axios.post(baseUrl + 'setup/store-id', {store_id: storeId}).then(response => {
                                    if (response.data.data) {
                                        resolve({storeId: response.data.data});
                                    } else if (response.data.message) {
                                        errorArea.html(`<div style="margin-top: 15px; font-weight: bold; text-align: center">${response.data.message}</div>`);
                                        resolve(false);
                                    }
                                }).catch(error => {
                                    resolve(false);
                                });
                            }
                        })
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Proceed',
                }).then((result) => {
                    if (result.value) {
                        this.form.slug = result.value.storeId;
                        toast.success(`Your store will be accessible at <a href="https://${this.form.slug}.${appSetting.baseUrl}">https://${this.form.slug}.${appSetting.baseUrl}</a> after completing this setup process.`)
                    }
                });
            },
            getSlug(str) {
                return window.slugify(str.trim()).toString().toLowerCase();
            },
            handleModalClosed(data) {
                console.log(this.selectedModal);
                if (data) {
                    const type = this.selectedModal.type === 'category' ? 'categories' : 'products';
                    if (this.selectedModal.index === null) {
                        this.form[type].push(data);
                    } else {
                        this.form[type][this.selectedModal.index] = data;
                    }
                }

                this.selectedModal.item = null;
                this.selectedModal.type = null;
                this.selectedModal.index = null;
            },
            openCreateModal(item, index, type) {
                this.selectedModal.item = item;
                this.selectedModal.type = type;
                this.selectedModal.index = index;
            },
            triggerUpload(tag) {
                this.uploadType = tag;

                if (this.uploadType === 'mobile_logo') {
                    this.form.mobile_logo = '';
                } else {
                    this.form.logo = '';
                }

                this.$root.$emit(eventName.triggerUpload, this.uploadId);
            },
            resetFields() {
                this.form = {
                    name: '',
                    description: '',
                    slug: '',
                    address: {
                        state: '',
                        address: '',
                        country: 'Nigeria'
                    },
                    contact: {
                        email: '',
                        phone_number: '',
                    },
                    logo: '',
                    mobile_logo: '',
                    categories: [],
                    products: [],
                    currency: {

                    }
                };
            },
            doSubmit() {
                if (this.currentStep < (this.steps.length - 1)) {
                    this.currentStep++;
                    return;
                }

                toast.confirm('Confirmation', 'Do you want to really complete this setup?', (result) => {
                    if (result) {
                        this.processing = true;
                        axios.post(baseUrl + 'stores/setup', {...this.form}).then(response => {
                            this.processing = false;

                            if (response.data.link) {
                                toast.success(response.data.message);
                                this.storeLink = response.data.link;
                            }

                        }).catch(error => {
                            this.processing = false;
                        });
                    }
                });
            },
        },
        beforeDestroy() {
            this.$root.$off(eventName.uploadError);
            this.$root.$off(eventName.uploadDone);
            this.$root.$off(eventName.uploadProgress);
        }
    }
</script>

<style lang="scss" scoped>
    @import "../../sass/variables";

    .progress-tab-container {
        width: 350px !important;
        padding: 15px 0;
        display: flex;
        justify-content: space-between;

        .progress-tab-item {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            line-height: 40px !important;
            display: inline-block;
            box-shadow: 0 10px 25px 0 rgba(50, 50, 93, 0.07), 0 5px 15px 0 rgba(0, 0, 0, 0.07);
            z-index: 2;

            &.active {
                background: $primary-color;
                color: white !important;

                &:after {
                    content: "";
                    position: absolute;
                    width: 80px !important;
                    height: 3px;
                    top: 50%;
                    left: 0;
                }
            }
        }

        .progress-tab-line {
            display: inline-block;
            height: 5px;
            width: 120px;

            &.active {
                background: $primary-color;
            }
        }
    }

    .custom-btn {
        button {
            border-radius: 0;
            padding: 7px 15px;

            & > span {
                display: inline-flex;
                justify-content: space-between;

                span {
                    min-width: 100px;
                }
            }

            i {
                line-height: 1.5;
            }
        }
    }

    .table-bordered th, .table-bordered td {
        border-color: #ccc !important;
    }

    .has-shadow {
        text-shadow: 0 0 2px #000;
    }
</style>
