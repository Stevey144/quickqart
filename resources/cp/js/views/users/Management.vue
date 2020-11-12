<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="m-0">Managements</h4>
                    <div>Manage all users and login profiles</div>
                </div>
                <div>
                    <router-link to="/management/new" class="btn btn-outline-primary">Create New User</router-link>
                </div>
            </div>
        </div>
        <div class="container page__container">
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="p-3">
                            <div>
                                <h6>Enter your filter criteria</h6>
                            </div>
                            <div class="input-group input-group-merge filter-header">
                                <input v-model="filter.filter" class="form-control" placeholder="Enter search term">
                                <input v-model="filter.from" class="form-control" type="date">
                                <input v-model="filter.to" class="form-control form-control-appended" type="date">
                                <button @click.prevent="doSearch()" class="btn btn-primary input-group-append">Search
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bmanagemented table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 60px">Image</th>
                                    <th style="min-width: 120px">Name</th>
                                    <th style="min-width: 120px">Email</th>
                                    <th style="min-width: 150px">User Type</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(management, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td>
                                        <img v-if="management.upload" class="img-raised"
                                             style="width: 35px; height: 35px; border-radius: 50%"
                                             :src="management.upload">
                                        <span v-else class="avatar avatar-sm mr-2">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                {{management.name.substr(0, 1)}}
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                        {{management.name}}
                                    </td>
                                    <td>
                                        {{management.email}}
                                    </td>
                                    <td>
                                        {{management.role_names.join(', ')}}
                                    </td>
                                    <td><span :class="management.is_suspended ? 'badge badge-danger' : 'badge badge-success'">{{management.is_suspended ? 'Suspended' : 'Active'}}</span></td>
                                    <td>{{management.created_at | timeago}}</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <router-link v-if="management.username" :to="'/users/' + management.username"
                                                   class="btn-link dropdown-item text-success">Edit record
                                                </router-link>
                                                <button v-if="!management.is_suspended" :disabled="processing"
                                                   @click.prevent="suspend(management, i)"
                                                   class="btn-link dropdown-item text-warning">Suspend account
                                                </button>
                                                <button :disabled="processing" v-else @click.prevent="reactivateAccount(management, i)"
                                                   class="btn-link dropdown-item text-primary">Reactivate account
                                                </button>
                                                <button :disabled="processing" @click.prevent="removeRecord(management, i)"
                                                   class="btn-link dropdown-item text-danger">Delete account
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!processData.fetching && (!dataBag || !dataBag.data.length)">
                                    <td colspan="8" class="text-center">No management to show
                                    </td>
                                </tr>
                                <tr v-if="processData.fetching">
                                    <td colspan="8" class="text-center">
                                        <spinner :stroke-color="'#042C59'"></spinner>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <pagination v-if="dataBag" :data="dataBag" :fetcher="fetcher"></pagination>
            </div>
        </div>
    </div>

</template>

<script>
    import {getAuthUser} from "../../init-app";
    import Spinner from "../../components/Spinner";
    import Upload from "../../components/Upload";
    import LoadingSpinner from "../../components/LoadingSpinner";
    import toast from "../../services/toast";
    import Pagination from "../../../../store/js/components/Pagination";
    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        name: "Management",
        components: {Pagination, LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processData: {
                    fetching: false,
                    suspension: false,
                    deleting: false,
                },
                selectedModal: null,
                managementLink: null,

                dataBag: null,
                fetcher: null,
                filter: {
                    filter: '',
                    to: '',
                    from: ''
                },
            }
        },
        mounted() {
            this.fetcher = {search: this.doSearch, fetch: this.getData};
            this.authUser = getAuthUser();
            this.doSearch();
        },
        computed: {
            processing() {
                return this.processData.fetching || this.processData.suspension || this.processData.deleting;
            }
        },
        methods: {
            getIndex(data) {
                return this.dataBag.data.findIndex(d => d.id.toString() === data.id.toString());
            },
            handleModalClosed(data) {
                if (data && this.dataBag && this.dataBag.data) {
                    const index = this.getIndex(data);
                    if (index > -1) {
                        this.dataBag.data[index] = data;
                    } else {
                        this.dataBag.data.push(data);
                    }
                }

                this.selectedModal = null;
            },
            reactivateAccount(item, i) {
                toast.confirm('Confirmation', 'Do you want to reactivate this user account?', (result) => {
                    if (result) {
                        this.processData.suspension = true;
                        axios.post(baseUrl + 'admin/users/' + (item.username || item.id) + '/reactivate', null).then(response => {
                            if (response.data && response.data.data) {
                                const index = this.getIndex(response.data.data);
                                if (index > -1) {
                                    this.dataBag.data[index] = response.data.data;
                                }
                            }
                            this.processData.suspension = false;
                        }).catch(error => {
                            this.processData.suspension = false;
                        });
                    }
                });
            },
            suspend(item, i) {
                Swal.fire({
                    title: 'Suspend Account',
                    html: `<small style="line-height: 1.5!important; margin-top: 15px">Write a note to the account owner on why this account is getting suspended. Note that if this account belongs to a store owner, the store will be unavailable to users after this process.</small><div class="swal-content-content"><div><textarea id="inputField" class="filter-input"></textarea></div>` +
                        `<div class="error-area"></div></div>`,
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            const errorArea = $('.error-area');
                            errorArea.html('');
                            const inputField = $('#inputField');
                            const reason = inputField.val().trim();
                            if (!reason) {
                                errorArea.html('<div style="margin-top: 15px; font-weight: bold; text-align: center">A reason is required to suspend account!</div>');
                                resolve(false);
                            } else {
                                axios.post(baseUrl + 'admin/users/' + (item.username || item.id) + '/suspend', {
                                    reason
                                }).then(response => {
                                    if (response.data && response.data.data) {
                                        const d = response.data.data;
                                        // console.log(d);
                                        const index = this.getIndex(d);
                                        // console.log(index);
                                        if (index > -1) {
                                            // console.log('was here');
                                            this.$set(this.dataBag.data, index, d);
                                        }
                                        resolve({data: d});
                                    } else {
                                        resolve(false);
                                    }
                                }).catch(error => {
                                    resolve(false);
                                });
                            }
                        })
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Suspend User',
                }).then((result) => {
                    if (result.value) {
                    }
                });
            },
            removeRecord(item, i) {
                toast.confirm('Confirmation', 'Do you want to delete this user completely. Note that the process is irreversible.', (result) => {
                    if (!result) {
                        return;
                    }

                    this.processData.deleting = true;

                    axios.delete(baseUrl + 'admin/users/' + (item.username || item.id)).then(response => {
                        const index = this.getIndex(item);
                        if (index > -1) {
                            this.dataBag.data.splice(index, 1);
                        }
                        this.processData.deleting = false;
                    }).catch(error => {
                        this.processData.deleting = false;
                    });
                });
            },
            doSearch(pageIndex = 0) {
                if (!pageIndex) {
                    const filters = [];
                    if (this.filter.filter) {
                        filters.push('filter=' + this.filter.filter);
                    }
                    if (this.filter.from) {
                        filters.push('from=' + this.filter.from);
                    }
                    if (this.filter.to) {
                        filters.push('to=' + this.filter.to);
                    }

                    this.lastSearchQuery = filters.length ? (filters.join('&')) : '';
                }

                this.getData((baseUrl + 'admin/users') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
            },
            getData(url) {
                this.processData.fetching = true;
                this.dataBag = null;
                axios.get(url).then(response => {
                    this.dataBag = response.data.data;
                    this.processData.fetching = false;
                }, error => {
                    this.processData.fetching = false;
                });
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
