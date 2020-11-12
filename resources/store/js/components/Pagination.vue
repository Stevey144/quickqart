<template>
    <div class="col-12" v-if="lastPage > 1">

        <ul class="pagination text-center">


            <li :class="'page-item' + (prevPageUrl ? '' : ' disabled')">
                <button @click.prevent="previousPage" class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true" class="material-icons">chevron_left</span>
                    <span>Prev</span>
                </button>
            </li>

            <li v-if="data" class="page-item disabled">
                                <span class="page-link">
                                    <span>{{data.from}} to {{data.to}} of {{data.total}} result</span>
                                </span>
            </li>
            <li v-if="data" class="page-item disabled">
                                <span class="page-link">
                                    <span>Page {{data.current_page}} of {{data.last_page}}</span>
                                </span>
            </li>

            <li :class="'page-item' + (nextPageUrl ? '' : ' disabled')">
                <button @click.prevent="nextPage" class="page-link" href="#" aria-label="Next">
                    <span>Next</span>
                    <span aria-hidden="true" class="material-icons">chevron_right</span>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'Pagination',
        data() {
            return {
                limit: 10
            }
        },
        props: ['data', 'fetcher'],

        methods: {
            previousPage() {
                this.fetcher.fetch(this.isApiResource ? this.data.links.prev : this.data.prev_page_url)
            },
            nextPage() {
                this.fetcher.fetch(this.isApiResource ? this.data.links.next : this.data.next_page_url)
            },
            selectPage(page) {
                if (page === '...') {
                    return;
                }

                this.fetcher.search(page)
            }
        },

        computed: {
            isApiResource() {
                return !!this.data.meta;
            },
            currentPage() {
                return this.isApiResource ? this.data.meta.current_page : this.data.current_page;
            },
            firstPageUrl() {
                return this.isApiResource ? this.data.links.first : null;
            },
            from() {
                return this.isApiResource ? this.data.meta.from : this.data.from;
            },
            lastPage() {
                return this.isApiResource ? this.data.meta.last_page : this.data.last_page;
            },
            lastPageUrl() {
                return this.isApiResource ? this.data.links.last : null;
            },
            nextPageUrl() {
                return this.isApiResource ? this.data.links.next : this.data.next_page_url;
            },
            perPage() {
                return this.isApiResource ? this.data.meta.per_page : this.data.per_page;
            },
            prevPageUrl() {
                return this.isApiResource ? this.data.links.prev : this.data.prev_page_url;
            },
            to() {
                return this.isApiResource ? this.data.meta.to : this.data.to;
            },
            total() {
                return this.isApiResource ? this.data.meta.total : this.data.total;
            },
            pageRange() {
                if (this.limit === -1) {
                    return 0;
                }

                if (this.limit === 0) {
                    return this.lastPage;
                }

                const current = this.currentPage;
                const last = this.lastPage;
                const delta = this.limit;
                const left = current - delta;
                const right = current + delta + 1;
                const range = [];
                const pages = [];
                let l;

                for (let i = 1; i <= last; i++) {
                    if (i === 1 || i === last || (i >= left && i < right)) {
                        range.push(i);
                    }
                }

                range.forEach(function (i) {
                    if (l) {
                        if (i - l === 2) {
                            pages.push(l + 1);
                        } else if (i - l !== 1) {
                            pages.push('...');
                        }
                    }
                    pages.push(i);
                    l = i;
                });

                return pages;
            }
        },
    }
</script>
