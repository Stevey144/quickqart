<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container mt-5 page__heading-container">
        </div>
        <div v-if="!fetching && form" class="container page__container">
            <div class="row">
                <div class="col col-md-auto" style="width: 250px">
                    <div class="card">
                        <div class="border-bottom">

                            <div v-if="form" class="card-body text-center">
                                <div class="mb-3">
                                    <img v-if="form.upload" :src="form.upload" alt="Logo"
                                         width="100" class="rounded-circle">
                                    <span v-else class="avatar mr-2"
                                          style="width: 6rem!important; height: 6rem!important; font-size: 2rem!important;">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-muted"
                                              style="color: #1377C9!important;">{{(form.name || 'QuickQart').substr(0, 1)}}</span>
                                    </span>
                                </div>
                                <p v-if="isEdit && user" class="font-weight-bold"> {{user.name}}</p>
                                <p v-else class="font-weight-bold">Create a new user record</p>
                            </div>
                        </div>

                        <div class="card-body p-0 mb-4">
                            <ul class="list-unstyled list-skills">
                                <li class="file-list-item mb-1">
                                    <a target="_blank" href="" @click.prevent="triggerUpload()">
                                        {{form.upload ? 'Change' : 'Upload a'}} Image</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div v-if="form" class="col-12">
                            <upload :trigger-id="uploadId"></upload>
                            <div class="card card-form">
                                <div class="col-12 bg-white py-3">
                                    <h4>{{isEdit ? 'Edit user record' : 'Create a user record'}}</h4>
                                </div>
                                <div class="row no-gutters">
                                    <form v-if="form" @submit.prevent="doSubmit()"
                                          class="col-lg-12 card-form__body card-body">
                                        <div class="row fadeInUp">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="fname">Name</label>
                                                            <input id="fname" type="text" name="name"
                                                                   v-model="form.name" class="form-control"
                                                                   placeholder="Business Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gender">Gender</label>
                                                            <select id="gender" name="gender"
                                                                    v-model="form.gender" class="custom-select w-100">
                                                                <option v-for="gender in ['Male', 'Female']"
                                                                        :value="gender">{{gender}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Email Address</label>
                                                            <input id="email" type="email" name="email"
                                                                   v-model="form.email" class="form-control"
                                                                   placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="username">Username</label>
                                                            <input id="username" name="username"
                                                                   v-model="form.username" class="form-control"
                                                                   placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Phone Number</label>
                                                            <input id="phone" type="text" name="phone_number"
                                                                   v-model="form.phone_number"
                                                                   class="form-control"
                                                                   placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="country">Country</label><br/>
                                                            <select id="country" required class="custom-select w-100"
                                                                    name="country"
                                                                    v-model="form.contact.country">
                                                                <option v-for="country in countries" :value="country">
                                                                    {{country}}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">State/Province</label><br/>
                                                            <input id="state" class="form-control"
                                                                   name="state"
                                                                   v-model="form.contact.state">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">Role</label><br/>
                                                            <select class="custom-select w-100"
                                                                   name="state"
                                                                    v-model="form.role">
                                                                <option v-for="role in roles" :value="role.name">{{role.name}}</option>
                                                            </select>
                                                            <small v-if="gettingRoles">Fetching roles....</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="address">Address</label><br/>
                                                            <textarea id="address" class="form-control"
                                                                      name="address"
                                                                      v-model="form.contact.address">
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mb-3 mt-4 text-center custom-btn">
                                                <div class="d-flex justify-content-center"></div>
                                                <button :disabled="processing" type="submit"
                                                        style="min-width: 200px" class="btn btn-primary">
                                                    <spinner v-if="processing"></spinner>
                                                    <span v-else>{{isEdit ? 'Update Details' : 'Create Record'}}</span>
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
        <div v-if="!fetching && hasError" class="container page__container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="text-center py-5 mt-5">
                        <h4 style="padding: 5px 15px; background: #28282d">An error was encountered.
                            <a href="#" @click.prevent="getData()">Click here to try again.</a></h4>
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
    export default {
        name: "AddEditUser",
        components: {LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                processing: false,
                fetching: false,
                hasError: false,
                canChangeRole: false,
                roles: [],
                countries: countryList.split('|'),
                uploading: false,
                gettingRoles: false,
                upload: null,
                uploadId: (Math.ceil(Math.random() * 10000000)),
            }
        },
        mounted() {
            this.startAfresh();
            this.authUser = getAuthUser();

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
                    this.form.upload = value.url;
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
                this.form.upload = '';
                this.$root.$emit(eventName.triggerUpload, this.uploadId);
            },
            resetFields() {
                this.form = {
                    name: '',
                    email: '',
                    username: '',
                    phone_number: '',
                    role: '',
                    contact: {
                        state: '',
                        address: '',
                        country: 'Nigeria'
                    },
                    upload: '',
                    gender: ''
                };
            },
            doSubmit() {
                toast.confirm('Confirmation', 'Do you want to ' + (this.isEdit ? 'update details' : 'create new user') + '?', (result) => {
                    if (result) {
                        this.processing = true;
                        let request;
                        if (this.isEdit) {
                            request = axios.put(baseUrl + 'profile/' + this.user.id, {...this.form});
                        } else {
                            request = axios.post(baseUrl + 'profile', {...this.form});
                        }
                        request.then(response => {
                            this.processing = false;

                            if (response.data.message) {
                                toast.success(response.data.message);

                                if (!this.isEdit) {
                                    this.resetFields();
                                }
                            }
                        }).catch(error => {
                            this.processing = false;
                        });
                    }
                });
            },
            getData() {
                this.fetching = true;
                this.hasError = false;
                axios.get(baseUrl + 'profile/' + this.username).then(response => {
                    if (response.data && response.data.data) {
                        this.user = response.data.data;

                        this.form = {
                            ...this.form,
                            ...this.user,
                            role: this.user.role_names ? this.user.role_names[0] : ''
                        };

                        this.getRoles();
                    }

                    this.fetching = false;
                }).catch(error => {
                    this.fetching = false;
                    this.hasError = true;
                });
            },
            getRoles() {
                this.gettingRoles = true;
                axios.get(baseUrl + 'roles').then(response => {
                    if (response.data && response.data.data) {
                        this.roles = response.data.data;
                    }

                    this.gettingRoles = false;
                }).catch(error => {
                    this.gettingRoles = false;
                });
            },
            startAfresh() {
                if (this.isEdit) {
                    this.getData();
                } else {
                    this.resetFields();
                    this.getRoles();
                }
            }
        },
        computed: {
            isEdit() {
                return this.$route.params.username !== 'new';
            },
            username() {
                return this.$route.params.username;
            }
        },
        watch: {
            $route(to, from) {
                this.startAfresh();
            }
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
            padding: 1.6875rem 10px!important;
        }
    }
</style>
