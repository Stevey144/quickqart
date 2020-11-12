<template>
    <div class="layout-login-centered-boxed"
         style="min-height: 100vh; vertical-align: center; display: flex; justify-content: center; align-items: center;">
        <div class="layout-login-centered-boxed__form align-items-center">
            <div class="card card-body">

                <div class="d-flex flex-column justify-content-center align-items-center mt-2 mb-2 navbar-light">
                    <a href="" class="text-center text-light-gray mb-3">
                        <img style="height: 50px" class="img" src="/parent/assets/img/logo.png">
                    </a>
                </div>

                <div class="mb-2 text-center" style="font-size: 18px; line-height: 1.5">
                    <router-view></router-view>
                </div>
                <div class="form-group mb-3 mt-3 text-center">
                    <button type="submit" onclick="location.href = '/'"
                            class="btn btn-outline-primary">Go to Home Page
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "NoLayout",
        methods: {
            goBack() {
                this.$router.back();
            }
        }
    }
</script>

<style scoped>
    .layout-login-centered-boxed {
        /*background-image: url("/assets/images/login/bg.jpg");*/
        background-position: center;
        background-size: cover;
    }

    body:before {
        content: '';
        position: fixed;
        display: block;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        /*background: rgba(0, 0, 0, 0.8)!important;*/
    }

    .layout-login-centered-boxed__form {
        width: 580px;
        max-width: 580px;
    }

    .form-control {
        border-width: 2px;
        min-height: 45px;
    }

    @media screen and (max-width: 375px) {
        .layout-login-centered-boxed__form {
            width: calc(100% - 20px);
            padding: 0 !important;
        }
    }
</style>
