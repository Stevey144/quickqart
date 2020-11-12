<template>
    <div class="card card-body px-4">

        <div class="text-center my-5">
            <h3>QuickQart</h3>
        </div>

        <form @submit.prevent="doSubmit()" class="text-left" novalidate>
            <div class="form-group">
                <label class="text-label text-uppercase">Email Address</label>
                <input id="email_2" type="text" required=""
                       v-model="email"
                       class="form-control form-control-prepended" placeholder="Email address">
            </div>
            <div class="form-group mb-1 px-5 pt-3">
                <button :disabled="processing" class="btn btn-block btn-primary py-2" type="submit"
                >{{processing ? '' : 'Get reset link'}}
                    <spinner v-if="processing"></spinner>
                </button>
            </div>
            <div class="form-group text-center mb-3 mt-4">
                <router-link to="/login" style="color:black !important;">Go to login page</router-link>
            </div>
        </form>
    </div>
</template>

<script>
    import toast from '../../services/toast';
    import Spinner from "../../components/Spinner";

    export default {
        name: "ForgotPassword",
        components: {Spinner},
        data() {
            return {
                email: '',
                resetting: false
            }
        },
        methods: {
            doSubmit() {
                this.resetting = true;
                axios.post(baseUrl + 'auth/password/recovery', {email: this.email}).then(response => {
                    this.resetting = false;
                    if (response && response.data) {
                        toast.success(response.data.message);
                        this.email = '';
                    }

                }).catch((error) => {
                    this.resetting = false;
                });
            }
        },
        computed: {
            appSetting() {
                return window.appSetting;
            },
            processing() {
                return this.resetting;
            },
        },
        mounted() {
            localStorage.removeItem(appSetting.jwt);
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
