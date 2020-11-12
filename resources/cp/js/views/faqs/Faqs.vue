<template>
    <div style="min-height: calc(100vh - 155px)">
        <div class="container page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="m-0">All FAQs</h4>
                </div>
                <div>
                    <button :disable="processing" @click.prevent="editFaq(false)" class="btn btn-outline-primary">Add
                        more FAQ
                    </button>
                </div>
            </div>
        </div>
        <div class="container page__container">
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="card">
                        <div class="p-3">
                            <div v-if="processing && !processData.fetching" class="text-center pb-3">
                                <spinner :stroke-color="'#042C59'"></spinner>
                                processing, please wait...
                            </div>
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
                            <table class="table table-bfaqed table-striped">
                                <thead class=" text-primary">
                                <tr>
                                    <th class="except-sm" style="width: 40px">#</th>
                                    <th style="min-width: 120px">Question</th>
                                    <th style="min-width: 120px">Answer</th>
                                    <th>Created At</th>
                                    <th class="action-header">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(faq, i) in faqs">
                                    <td>{{ (i + 1)}}</td>
                                    <td>{{faq.question}}</td>
                                    <td>{{faq.answer | preview(70)}}</td>
                                    <td style="min-width: 170px">{{faq.created_at | timeago}}</td>
                                    <td>
                                        <div class="dropdown ml-auto">
                                            <a href="#" class="dropdown-toggle text-muted"
                                               data-caret="false" data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button :disabled="processing" @click.prevent="editFaq(faq)"
                                                        class="btn-link dropdown-item text-primary">Edit FAQ
                                                </button>
                                                <button :disabled="processing" @click.prevent="removeRecord(faq, i)"
                                                        class="btn-link dropdown-item text-danger">Delete FAQ
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!processData.fetching && !faqs.length">
                                    <td colspan="8" class="text-center">No faq to show
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

    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        name: "Faqs",
        components: {LoadingSpinner, Upload, Spinner},
        data() {
            return {
                authUser: {},
                user: null,
                form: null,
                isEdit: false,
                processData: {
                    fetching: false,
                    deleting: false,
                    updating: false,
                },
                selectedModal: null,
                faqLink: null,
                faqs: [],
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
                return this.processData.fetching || this.processData.deleting || this.processData.updating;
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
            editFaq(item) {
                item = item ? {...item} : {question: '', answer: ''};
                Swal.fire({
                    title: item.id ? 'Edit FAQ' : 'Create FAQ',
                    html: `<div class="swal-content-content"><div>
                           <input value="${item.question}" style="font-size: 15px" placeholder="Enter FAQ here" id="questionField" class="filter-input">
                           <textarea class="filter-input" placeholder="Put the answer here" id="answerField">${item.answer}</textarea></div>
                           <div class="error-area"></div>
                           </div>`,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            const errorArea = $('.error-area');
                            errorArea.html('');
                            const data = {
                                question: $('#questionField').val().trim(),
                                answer: $('#answerField').val().trim(),
                            };

                            if (!data.question || !data.answer) {
                                errorArea.html('<div style="margin-top: 15px; font-weight: bold; text-align: center; font-size: 13px;">All fields are required!</div>');
                                resolve(false);
                            } else {
                                resolve(data);
                            }
                        })
                    },
                    showCancelButton: true,
                    confirmButtonText: item.id ? 'Update Record' : 'Create Record',
                }).then((result) => {
                    if (result.value) {
                        this.processData.updating = true;
                        let request;

                        if (item.id) {
                            request = axios.put(`${baseUrl}admin/faqs/${item.id}`, {...result.value});
                        } else {
                            request = axios.post(`${baseUrl}admin/faqs`, {...result.value});
                        }
                        request.then(response => {
                            if (response.data.data) {
                                const d = response.data.data;
                                const index = this.faqs.findIndex(f => f.id.toString() === d.id.toString())

                                if (index > -1) {
                                    this.faqs[index] = d;
                                } else {
                                    this.faqs.push(d);
                                }
                                toast.success(response.data.message);
                            }
                            this.processData.updating = false;
                        }).catch(error => {
                            this.processData.updating = false;
                        });
                    }
                });
            },
            removeRecord(item, i) {
                toast.confirm('Confirmation', 'Do you want to delete this faq. Note that the process is irreversible.', (result) => {
                    if (!result) {
                        return;
                    }

                    this.processData.deleting = true;

                    axios.delete(baseUrl + 'admin/faqs/' + item.id).then(response => {
                        if (response.data.message) {
                            this.faqs.splice(i, 1);
                            toast.success(response.data.message);
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

                this.getData((baseUrl + 'admin/faqs') + (pageIndex ? ('?page=' + pageIndex + '&') : '?') + this.lastSearchQuery)
            },
            getData(url) {
                this.processData.fetching = true;
                this.faqs = [];
                axios.get(url).then(response => {
                    this.faqs = response.data.data;
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
