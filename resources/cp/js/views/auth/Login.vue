<template>
    <div class="card card-body px-4">

    </div>
</template>

<script>
    import LoadingSpinner from "../../components/LoadingSpinner";
    import Spinner from "../../components/Spinner";

    export default {
        name: "Login",
        components: {Spinner, LoadingSpinner},
        data() {
            return {
                display: 'login',
                login_in: false,
                social_login_in: false,
                username: '',
                password: '',
                resetEmail: '',
                resetting: false
            }
        },
        methods: {
        },
        computed: {
            nextUrl() {
                let next = '';
                if (this.$route.query.next) {
                    next = this.$route.query.next;
                }
                return next;
            }
        },
        mounted() {
            localStorage.removeItem(appSetting.jwt);

            const next = this.nextUrl;
            location.href = window.rootUrl + `/login${next ? ('?next=' + next) : ''}`;
        }
    }
</script>

<style scoped>
    .card-body {
        min-width: 400px !important;
        background: rgba(255, 255, 255, 0.45) !important;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.78);
        height: 40px;
    }

    @media screen and (max-width: 399px) {
        .card-body {
            min-width: calc(100vw - 10px) !important;
            width: calc(100vw - 10px) !important;
        }
    }
</style>
