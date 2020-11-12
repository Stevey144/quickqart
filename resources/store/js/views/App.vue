<template>
    <component :is="layout"></component>
</template>

<script>
    import {getAuthUser, initApp} from "../init-app";

    const default_layout = "default";

    export default {
        components: {},
        data() {
            return {
                layout: 'no-layout'
            }
        },
        computed: {
            authUser() {
                return getAuthUser();
            },
            isAdmin() {
                const authUser = this.authUser;
                return authUser && !authUser.role.includes(appSetting.roles.STUDENT);
            },
            appSetting() {
                return window.appSetting;
            }
        },
        mounted() {
            this.setLayout();
        },
        watch: {
            $route(to, from) {
                console.log(from, to);
                this.setLayout();
                this.$root.$emit(eventName.scrollToTop, true);
            }
        },
        methods: {
            setLayout() {
                this.layout = (this.$route.meta.layout || default_layout) + "-layout";
                setTimeout(() => {
                    initApp();
                }, 500)
            }
        }
    }
</script>
