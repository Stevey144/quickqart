<template>
    <div class="mdk-header-layout js-mdk-header-layout">
        <top-nav></top-nav>
        <div class="mdk-header-layout__content page">
            <app-menu></app-menu>
            <div class="m-mt">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import AppMenu from "../components/menu/AppMenu";
    import TopNav from "../components/menu/TopNav";

    export default {
        name: "AdminLayout",
        components: {TopNav, AppMenu},
        mounted() {
            $(document.body).removeClass('layout-fluid layout-sticky-subnav').addClass('layout-default');

            this.$root.$on(eventName.scrollToTop, data => {
                if (document.getElementById('top-marker') && document.getElementById('navbarNav')) {
                    $("html, body").animate({scrollTop: $("#top-marker").offset().top - $("#navbarNav").height()}, 800);
                }
            });
        }
    }
</script>

<style scoped>

</style>
