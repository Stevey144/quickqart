<template>
    <div>
        <div class="main-content bg-color">
            <div class="container">
                <div class="user-account text-center" style="">
                    <div class="account-content">
                        <h1>Reset Password</h1>
                        <form class="tr-form" @submit.prevent="doResetPassword">
                            <div class="form-group">
                                <input type="email" v-model="email" class="form-control" id="exampleInputEmail1"
                                       placeholder="Enter your email address" required>
                            </div>
                            <div class="form-group mt-3">
                                <input type="password" v-model="password" class="form-control"
                                       id="exampleInputPassword1"
                                       placeholder="Enter new password">
                            </div>
                            <div class="form-group mt-3">
                                <input type="password" v-model="cPassword" class="form-control"
                                       id="exampleInputPassword1c"
                                       placeholder="Confirm new password">
                            </div>
                            <button type="submit" :disabled="processing" class="btn btn-primary mb-4"
                                    :class="processing ? '' : ''">
                                <loading-spinner v-if="processing" :svg="'35px'" :svg-only="true"/>
                                {{processing ? '' : 'Reset Password'}}
                            </button>
                        </form>
                    </div><!-- /.account-content -->
                </div><!-- /.user-account  -->
            </div><!-- /.container -->
        </div><!-- /.main-content -->
    </div>
</template>

<script>
    import toast from "../../services/toast";
    import LoadingSpinner from "../../components/LoadingSpinner";

    export default {
        name: "ResetPassword",
        components: {LoadingSpinner},
        data() {
            return {
                password: '',
                cPassword: '',
                email: '',
                processing: false
            }
        },
        methods: {
            doResetPassword() {
                const token = this.$route.params.token;
                this.processing = true;
                axios.post('/api/auth/password/reset', {
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.cPassword,
                    token: token
                }).then(response => {
                    if (response && response.data) {
                        toast.success(response.data.message);

                        this.email = '';
                        this.password = '';
                        this.cPassword = '';
                    }

                    this.processing = false;
                }).catch((error) => {
                    this.processing = false;
                });
            }
        }
    }
</script>
<style>
    .form-control {
        height: 40px;
    }
</style>
