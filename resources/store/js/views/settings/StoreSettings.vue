<template>
    <div style="min-height: calc(100vh - 155px)">
        <!--        <div v-if="form && form.banner" class="banner-background" :style="'background-image: url(' + form.banner + ')'"></div>-->
        <div class="container page__heading-container">
            <div class="page__heading">
                <div class="mt-3">
                    <h4 class="header-heading font-weight-bold m-0 text-center">Update store details</h4>
                </div>
            </div>
        </div>
        <div v-if="!fetching && store" class="container page__container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-3">
                    <div class="card">
                        <div class="border-bottom">

                            <div v-if="form" class="card-body text-center">
                                <div class="mb-3">
                                    <img v-if="form.logo || form.mobile_logo"
                                         :src="form.logo || form.mobile_logo" alt="Logo"
                                         width="100" class="rounded-circle">
                                    <span v-else class="avatar mr-2"
                                          style="width: 6rem!important; height: 6rem!important; font-size: 2rem!important;">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-muted"
                                              style="color: #1377C9!important;">{{store.name.substr(0, 1)}}</span>
                                    </span>
                                </div>
                                <p class="font-weight-bold"> {{store.name}}</p>
                                <div>
                                    <span v-if="form.slug">Store ID: {{form.slug}} <br><br></span>
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
                                        {{form.mobile_logo ? 'Change' : 'Upload a'}} mobile logo</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div v-if="form" class="col-12">
                            <upload :trigger-id="uploadId"></upload>
                            <div class="card card-form">
                                <div class="row no-gutters">
                                    <form v-if="form" @submit.prevent="doSubmit()"
                                          class="col-lg-12 card-form__body card-body">
                                        <div class="row fadeInUp">
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
                                                            <select id="lga" required class="form-control"
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
                                                        <select required class="form-control"
                                                                name="state" v-model="form.currency">
                                                            <option v-for="currency in currencies" :value="currency">
                                                                {{currency.name}} ({{currency.code}})
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!--                                                <div class="col-md-6 px-0">-->
                                                <!--                                                    <label>Social Account</label>-->
                                                <!--                                                </div>-->
                                                <!--                                                <div class="row">-->
                                                <!--                                                    <div class="col-md-6 mb-3">-->
                                                <!--                                                        <input required class="form-control" placeholder="Facebook username"-->
                                                <!--                                                               name="state" v-model="form.social.facebook">-->
                                                <!--                                                    </div>-->
                                                <!--                                                    <div class="col-md-6 mb-3">-->
                                                <!--                                                        <input required class="form-control" placeholder="Twitter handle"-->
                                                <!--                                                               name="state" v-model="form.social.twitter">-->
                                                <!--                                                    </div>-->
                                                <!--                                                    <div class="col-md-6 mb-3">-->
                                                <!--                                                        <input required class="form-control" placeholder="Youtube Channel"-->
                                                <!--                                                               name="state" v-model="form.social.youtube">-->
                                                <!--                                                    </div>-->
                                                <!--                                                    <div class="col-md-6 mb-3">-->
                                                <!--                                                        <input required class="form-control" placeholder="Instagram username"-->
                                                <!--                                                               name="state" v-model="form.social.instagram">-->
                                                <!--                                                    </div>-->

                                                <!--                                                </div>-->

                                                <div class="form-group">
                                                    <label for="desc">Description</label>
                                                    <textarea v-model="form.description" required id="desc" rows="7"
                                                              class="form-control"
                                                              placeholder="Write briefly about your store"></textarea>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mb-5 mt-4 text-center custom-btn">
                                                <div class="d-flex justify-content-center"></div>
                                                <button :disabled="processing" type="submit"
                                                        style="min-width: 200px" class="btn btn-primary">
                                                    <spinner :stroke-color="'#ffffff'" v-if="processing"></spinner>
                                                    <span v-else>Update Details</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="fetching" class="container page__container">
            <div class="row d-flex justify-content-center">
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
    import Upload from "../../components/Upload";
    import Spinner from "../../components/Spinner";
    import {getAuthUser} from "../../init-app";
    import toast from "../../services/toast";
    import LoadingSpinner from "../../components/LoadingSpinner";
    import {currencyList} from "../../../../constants";

    export default {
        name: "StoreSettings",
        components: {LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processing: false,
                fetching: false,
                store: null,
                canChangeRole: false,
                roles: [],
                uploading: false,
                upload: null,
                uploadId: (Math.ceil(Math.random() * 10000000)),
                currentStep: 0,
                uploadType: null,
                countries: window.countryList.split('|'),
                storageID: 'qq_ss_d',
                currencies: currencyList
            }
        },
        mounted() {
            this.getData();
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

                    if (this.uploadType === 'banner') {
                        this.form.banner = value.url;
                    } else if (this.uploadType === 'logo') {
                        this.form.logo = value.url;
                    } else {
                        this.form.mobile_logo = value.url;
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
            triggerUpload(tag) {
                this.uploadType = tag;

                if (this.uploadType === 'banner') {
                    this.form.banner = '';
                } else if (this.uploadType === 'logo') {
                    this.form.logo = '';
                } else {
                    this.form.mobile_logo = '';
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
                    social: {
                        facebook: '',
                        instagram: '',
                        youtube: '',
                    },
                    contact: {
                        email: '',
                        phone_number: '',
                    },
                    logo: '',
                    mobile_logo: '',
                    banner: '',
                    currency: {
                        symbol: 'NGN',
                        name: 'Naira',
                        code: '₦',
                        rate: 1
                    }
                };
            },
            doSubmit() {
                toast.confirm('Confirmation', 'Do you want to update your store information?', (result) => {
                    if (result) {
                        this.processing = true;
                        axios.put(baseUrl + 'stores', {...this.form}).then(response => {
                            this.processing = false;

                            if (response.data.message) {
                                toast.success(response.data.message);
                            }

                        }).catch(error => {
                            this.processing = false;
                        });
                    }
                });
            },
            getData() {
                this.fetching = true;
                axios.get(baseUrl + 'stores').then(response => {
                    if (response.data && response.data.data) {
                        this.store = response.data.data

                        this.form = {
                            ...this.form,
                            ...this.store
                        }
                    }

                    this.fetching = false;
                }).catch(error => {
                    this.fetching = false;
                });
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
    @import "../../../sass/variables";

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

    .banner-background {
        position: absolute;
        display: block;
        top: 0;
        right: 0;
        left: 0;
        height: 100%;
        z-index: 0;
        opacity: 0.65;
        background-size: cover;
        background-position: top;
    }

    .banner-background:before {
        content: "";
        position: absolute;
        display: block;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        z-index: 2;
        background: linear-gradient(to bottom, rgba(43, 43, 49, 0) 0%, #010101 100%);
    }

    .has-shadow {
        text-shadow: 0 0 2px #000;
    }

    @media screen and (max-width: 768px) {
        .card-form .card-body {
            padding: 1.6875rem 10px !important;
        }
    }
</style>
