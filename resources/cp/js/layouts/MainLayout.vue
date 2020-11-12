<template>
    <div>
        <div class="mdk-header-layout js-mdk-header-layout">

            <top-nav></top-nav>

            <div class="mdk-header-layout__content">

                <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
                    <div class="mdk-drawer-layout__content page">
                        <router-view></router-view>
                    </div>

                    <drawable-menu></drawable-menu>

                </div>

            </div>

        </div>
    </div>

</template>

<script>
    import DrawableMenu from "../components/menu/DrawableMenu";
    import TopNav from "../components/menu/TopNav";
    export default {
        name: "AdminLayout",
        components: {TopNav, DrawableMenu},
        mounted() {
            $(document.body).removeClass('layout-fluid layout-sticky-subnav').addClass('layout-default');

            this.$root.$on(eventName.scrollToTop, data => {
                if (document.getElementById('top-marker') && document.getElementById('navbarNav')){
                    $("html, body").animate({scrollTop: $("#top-marker").offset().top - $("#navbarNav").height()}, 800);
                }
            });
        }
    }
</script>

<style scoped>

</style>
