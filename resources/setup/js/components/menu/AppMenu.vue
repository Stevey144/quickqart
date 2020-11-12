<template>
    <div class="page__header page__header-nav mb-0">
        <div class="container page__container">
            <div style="padding: 15px 0 10px">
                <h4>Welcome, {{authUser.name}}</h4>
            </div>
        </div>
    </div>
</template>

<script>
    import {getAuthUser} from "../../init-app";

    export default {
        name: "AppMenu",
        computed: {
            authUser() {
                return getAuthUser();
            }
        },
        methods: {
            logout(event) {
                event.preventDefault();
                localStorage.removeItem(appSetting.jwt);
                localStorage.removeItem(appSetting.id_tkn);
                location.href = "/login";
            },
        }
    }
</script>

<style lang="scss" scoped>
</style>
