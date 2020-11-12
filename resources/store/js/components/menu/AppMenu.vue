<template>
    <div class="page__header page__header-nav mb-0">
        <div class="fixed-header-mobile container page__container">
            <div class="navbar navbar-secondary navbar-light navbar-expand-sm p-0">
                <div class="navbar-collapse collapse" id="secondaryNavbar">
                    <ul class="nav navbar-nav">
                        <li v-for="m in menu" class="nav-item">
                            <router-link :to="m.url" class="nav-link">{{m.name}}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {getAuthUser} from "../../init-app";

    export default {
        name: "AppMenu",
        data() {
            return {
                menu: [
                    {name: 'Dashboard', url: '/'},
                    {name: 'Orders', url: '/orders'},
                    {name: 'Products', url: '/products'},
                    {name: 'Categories', url: '/categories'},
                    // {name: 'My Stores', url: '/my-stores'},
                ]
            }
        },
        computed: {
            authUser() {
                return getAuthUser();
            },
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
