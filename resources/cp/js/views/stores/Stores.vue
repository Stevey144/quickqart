<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="m-0">Stores</h4>
                    <div>Manage all stores and products</div>
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
                                    <th style="min-width: 100px">Page Visits</th>
                                    <th style="min-width: 100px">Status</th>
                                    <th style="min-width: 150px">Contact</th>
                                    <th style="min-width: 150px">Location</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(store, i) in (dataBag || {data:[]}).data">
                                    <td>{{ (i + 1)}}</td>
                                    <td><a target="_blank" :href="`${store.url}`">{{store.name}}</a></td>
                                    <td>
                                        {{store.user ? store.user.name : ''}}
                                    </td>
                                    <td>
                                        {{store.daily_visitors}} today / {{store.total_visitors}} total
                                    </td>
                                    <td>
                                        <div v-if="store.user">
                                            <span :class="store.user.is_suspended ? 'badge badge-danger' : 'badge badge-success'">
                                                {{store.user.is_suspended ? 'Suspended' : 'Active'}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div v-if="store.contact">
                                            <div v-if="store.contact.phone_number">
                                                <small>{{store.contact.phone_number}}</small>
                                            </div>
                                            <div v-if="store.contact.email">
                                                <small>{{store.contact.email}}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span v-if="store.address">
                                            {{[store.address.address, store.address.state, store.address.country].join(', ')}}
                                        </span>
                                    </td>
                                    <td>{{store.created_at | timeago}}</td>
                                </tr>
                                <tr v-if="!processing.fetching && (!dataBag || !dataBag.data.length)">
                                    <td colspan="9" class="text-center">No store to show
                                    </td>
                                </tr>
                                <tr v-if="processing.fetching">
                                    <td colspan="9" class="text-center">
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

    export default {
        name: "Stores",
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
                this.processing.fetching = false;
                this.dataBag = null;
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
