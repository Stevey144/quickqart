<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <h4 class="m-0">Stores</h4>
                <button @click.prevent="openCreateModal(false)" class="btn btn-outline-primary ml-3">Add new store</button>
            </div>
        </div>
        <div class="container page__container">
            <div v-if="!processing.fetching" class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="p-3">
                            <div class="input-group input-group-merge">
                                <input v-model="filter.filter" class="form-control" placeholder="Enter search term">
                                <input v-model="filter.from" class="form-control" type="date">
                                <input v-model="filter.to" class="form-control form-control-appended" type="date">
                                <button @click.prevent="doSearch()" class="btn btn-primary input-group-append">Search</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bstoreed table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Name</th>
                                    <th style="min-width: 120px">Owner</th>
                                    <th style="min-width: 150px">Contact</th>
                                    <th style="min-width: 150px">Location</th>
                                    <th>Created At</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(store, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td><router-link :to="`/stores/${store.slug}`">{{store.name}}</router-link></td>
                                    <td>
                                        {{store.user ? store.user.name : ''}}
                                    </td>
                                    <td>
                                        <span v-if="store.address">
                                            {{[store.address.address, store.address.state, store.address.country].join(', ')}}
                                        </span>
                                    </td>
                                    <td>
                                        <div v-if="store.contact">
                                            <div v-if="store.contact.phone_number">
                                                <i class="mr-2 fa fa-phone"></i>{{store.contact.phone_number}}
                                            </div>
                                            <div v-if="store.contact.email">
                                                <i class="mr-2 fa fa-envelope"></i>{{store.contact.email}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{store.created_at | timeago}}</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false"
                                               data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a v-if="!store.is_suspended" href="#" @click.prevent="suspend(store, i)"
                                                   class="btn-link dropdown-item text-warning">Suspend
                                                </a>
                                                <a v-else href="#" @click.prevent="suspend(store, i)"
                                                   class="btn-link dropdown-item text-primary">Activate account
                                                </a>
                                                <a href="#" @click.prevent="removeRecord(store, i)"
                                                   class="btn-link dropdown-item text-danger">Remove
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!dataBag || !dataBag.data.length">
                                    <td colspan="8" class="text-center">No store to show
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <pagination v-if="dataBag" :data="dataBag" :fetcher="fetcher"></pagination>
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
    import {getAuthUser} from "../../init-app";
    import Spinner from "../../components/Spinner";
    import Upload from "../../components/Upload";
    import LoadingSpinner from "../../components/LoadingSpinner";
    import toast from "../../services/toast";
    import Pagination from "../../../../store/js/components/Pagination";

    export default {
        name: "SingleStore",
        components: {Pagination, LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processing: {
                    fetching: false
                },
                selectedModal: null,
                storeLink: null,

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
            suspend(item, i) {

            },
            removeRecord(item, i) {
                toast.confirm('Confirmation', 'Do you want to delete this store. Note that all the' +
                    ' products added to this store will be affected and that the process is irreversible.', (result) => {
                    if (!result) {
                        return;
                    }

                    this.processing.deleting = true;

                    axios.delete(baseUrl + 'admin/stores/' + item.slug).then(response => {
                        const index = this.getIndex(data);
                        if (index > -1) {
                            this.dataBag.data.splice(index, 1);
                        }
                        this.processing.deleting = false;
                    }).catch(error => {
                        this.processing.deleting = false;
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

                this.getData((baseUrl + 'admin/stores') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
            },
            getData(url) {
                this.processing.fetching = true;
                axios.get(url).then(response => {
                    this.dataBag = response.data.data;
                    this.processing.fetching = false;
                }, error => {
                    this.processing.fetching = false;
                });
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
