<template>
    <div class="card card-body px-4">

<!--        <div class="text-center my-5">-->
<!--            <h3>QuickQart</h3>-->
<!--        </div>-->

<!--        <form @submit.prevent="doLogin()" class="text-left" novalidate>-->
<!--            <div class="form-group">-->
<!--                <label class="text-label text-uppercase">User Id</label>-->
<!--                <div class="input-group">-->
<!--                    <input id="email_2" type="text" required=""-->
<!--                           v-model="username"-->
<!--                           class="form-control" placeholder="Username or email">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <label class="text-label text-uppercase">Password</label>-->
<!--                <div class="input-group">-->
<!--                    <input id="password_2" v-model="password"-->
<!--                           type="password" required="" class="form-control"-->
<!--                           placeholder="Password">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group mb-1 px-5 pt-3">-->
<!--                <button :disabled="processing" class="btn btn-block btn-primary py-2" type="submit"-->
<!--                >{{login_in ? '' : 'Login'}}<spinner v-if="login_in"></spinner></button>-->
<!--            </div>-->
<!--            <div class="form-group text-center mb-3 mt-4">-->
<!--                <router-link to="/forgot-password" style="color: black !important;">Forgot password?</router-link>-->
<!--            </div>-->
<!--        </form>-->


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
            // doLogin() {
            //     this.login_in = true;
            //
            //     axios.post(baseUrl + 'auth/login', {email: this.username, password: this.password}).then(response => {
            //         if (response && response.data && response.data.token) {
            //             // toast.success('Logged In successfully, please wait...');
            //             localStorage.setItem(appSetting.jwt, response.data.token);
            //             localStorage.setItem(appSetting.id_tkn, response.data.id_token);
            //             let home = '/';
            //             setTimeout(() => {
            //                 if (this.nextUrl) {
            //                     location.href = this.nextUrl;
            //                 } else {
            //                     location.href = home;
            //                 }
            //             }, 1000);
            //         } else {
            //             this.login_in = false;
            //         }
            //
            //     }).catch((error) => {
            //         this.login_in = false;
            //     });
            // }
        },
        computed: {
            // appSetting() {
            //     return window.appSetting;
            // },
            // processing() {
            //     return this.login_in || this.social_login_in;
            // },
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

            // this.$root.$on(eventName.socialLogin, data => {
            //     this.social_login_in = data;
            // });

            location.href = window.rootUrl + '/login';
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
    }

    @media screen and (max-width: 399px) {
        .card-body {
            min-width: calc(100vw - 10px) !important;
            width: calc(100vw - 10px) !important;
        }
    }
</style>
