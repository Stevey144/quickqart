<template>
    <loading-spinner v-if="verifying"></loading-spinner>
</template>

<script>
    import LoadingSpinner from "../../components/LoadingSpinner";

    export default {
        name: "VerifyToken",
        components: {LoadingSpinner},
        data() {
            return {
                verifying: false
            }
        },
        methods: {
            verifyToken() {
                this.verifying = true;
                const token = this.$route.params.token;
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                axios.get(baseUrl + 'verify-token').then(response => {

                    if (response.data && response.data.data) {
                        localStorage.setItem(appSetting.jwt, token);
                        localStorage.setItem(appSetting.id_tkn, JSON.stringify(response.data.data));
                        if (response.data.stores && response.data.stores.length) {
                            localStorage.setItem(appSetting.stores_tkn, JSON.stringify(response.data.stores));
                            localStorage.setItem(appSetting.pref_store, response.data.stores[0].slug);
                            localStorage.setItem(appSetting.pref_currency,
                                response.data.stores[0].currency ? response.data.stores[0].currency.code : '₦');
                        }
                        location.href = "/";
                    } else {
                        this.verifying = true;
                    }

                }).catch(error => {
                    this.verifying = true;
                });
            }
        },
        mounted() {
            localStorage.clear();
            this.verifyToken();
        },
        watch: {
            $route(to, from) {
                this.verifyToken();
            }
        }
    }
</script>

<style scoped>

</style>
