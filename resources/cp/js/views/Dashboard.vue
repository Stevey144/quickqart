<template>
    <div>
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center justify-content-between">
                <h4 class="header-heading m-0">Dashboard</h4>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div v-if="!processing.fetching && dashboardData" class="row card-group-row">
                <div class="col-lg-3 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="avatar">
                        <span class="bg-soft-success avatar-title rounded-circle text-center text-success">
                            <i class="material-icons icon-40pt">person</i>
                        </span>
                        </div>
                        <div class="flex text-right">
                            <div class="card-header__title text-muted mb-2">Stores</div>
                            <div class="text-amount">{{dashboardData.stores}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="avatar">
                        <span class="bg-soft-primary avatar-title rounded-circle text-center text-primary">
                            <i class="material-icons icon-40pt">people</i>
                        </span>
                        </div>
                        <div class="flex text-right">
                            <div class="card-header__title text-muted mb-2">Products</div>
                            <div class="text-amount">{{dashboardData.products}}</div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="avatar">
                        <span class="bg-soft-primary avatar-title rounded-circle text-center text-primary">
                            <i class="material-icons icon-40pt">people</i>
                        </span>
                        </div>
                        <div class="flex text-right">
                            <div class="card-header__title text-muted mb-2">Categories</div>
                            <div class="text-amount">{{dashboardData.categories}}</div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-12 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="avatar">
                        <span class="bg-soft-warning avatar-title rounded-circle text-center text-warning">
                            <i class="material-icons text-warning icon-40pt">card_giftcard</i>
                        </span>
                        </div>
                        <div class="flex text-right">
                            <div class="card-header__title text-muted mb-2">Transactions</div>
                            <div class="text-amount">{{dashboardData.orders}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHART -->
            <div v-if="!processing.fetching && dashboardData" class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header bg-white d-flex align-items-center">
                            <h4 class="card-header__title mb-0">Statistics</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <div id="ordersChart" style="height: 300px" class="chart-canvas"></div>
                            </div>
                        </div>
                    </div>

                </div>
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
    import LoadingSpinner from "../../../store/js/components/LoadingSpinner";
    export default {
        name: "Dashboard",
        components: {LoadingSpinner},
        data() {
          return {
              processing: {
                  fetching: false
              },
              dashboardData: {}
          }
        },
        methods: {
            getDashboardData() {
                this.processing.fetching = true;

                axios.get(baseUrl + 'dashboard').then(response => {
                    if (response.data) {
                        this.dashboardData = response.data.data;
                        setTimeout(() => {
                            new Chartist.Line('#ordersChart', {
                                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                                series: this.dashboardData.months
                            }, {
                                // low: 0,
                                // showArea: true
                            }, 1500);
                        });
                    }
                    this.processing.fetching = false;
                }).catch(error => {
                    this.processing.fetching = false;
                });
            },
        },
        mounted() {
            this.getDashboardData();
        }
    }
</script>

<style scoped>

</style>
