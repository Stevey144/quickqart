<template>
    <div class="layout-login-centered-boxed">
        <div class="layout-login-centered-boxed__form">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AuthLayout",
        computed: {
            appSetting() {
                return appSetting;
            }
        },
        mounted() {
        },
        methods: {
        }
    }
</script>

<style scoped>
    .layout-login-centered-boxed {
        min-height: 100vh!important;
        background-color: rgba(15, 91, 154, 0.67);

        background-size: cover;
        background-image: url("/parent/assets/images/account-bg.jpg");
        background-repeat: no-repeat;
    }

    .layout-login-centered-boxed__form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .layout-login-centered-boxed__form .card-body {
        padding-bottom: 70px;
    }
</style>
