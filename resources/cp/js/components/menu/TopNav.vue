<template>
    <div id="header" class="mdk-header js-mdk-header m-0" data-fixed>
        <div class="mdk-header__content">
            <div
                class="navbar navbar-expand-sm navbar-main navbar-light bg-white pl-md-0 pr-0" id="navbar" data-light>
                <div class="container-fluid pr-0">
                    <button
                        class="navbar-toggler navbar-toggler-custom d-lg-none d-flex mr-navbar"
                        type="button"
                        data-toggle="sidebar">
                        <span class="material-icons">short_text</span>
                    </button>
                    <div class="d-flex sidebar-account flex-shrink-0">
                        <router-link to="/" class="flex d-flex align-items-center text-underline-0">
                          <span class="mr-1 text-white">
                            <img src="/parent/assets/img/logo.png" height="42" style="opacity: 1!important"/>
                          </span>
                            <!-- <span class="flex d-flex flex-column text-white">
                              <strong class="sidebar-brand">QuickQart</strong>
                            </span> -->
                        </router-link>
                    </div>

                    <ul class="nav navbar-nav d-none d-md-flex ml-auto">
                    </ul>

                    <div class="dropdown">
                        <a href="#account_menu"
                            class="dropdown-toggle navbar-toggler navbar-toggler-dashboard border-left d-flex align-items-center ml-navbar"
                            data-toggle="dropdown">
                            <span class="avatar avatar-sm mr-2">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    {{authUser.name.substr(0, 1)}}</span>
                              </span>
                            <span class="ml-1 d-none d-md-inline-flex">
                                <span class="text-light">{{authUser.name}}</span>
                            </span>
                        </a>
                        <div id="company_menu" class="dropdown-menu dropdown-menu-right navbar-company-menu">
                            <div class="dropdown-item d-flex align-items-center py-2 navbar-company-info py-3">
                                <span class="flex d-flex flex-column">
                                  <strong class="h5 m-0">{{authUser.name}}</strong>
                                  <small class="text-muted text-uppercase">{{authUser.role.join(', ')}}</small>
                                </span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <router-link
                                class="dropdown-item d-flex align-items-center py-2"
                                to="/profile/me"><span class="material-icons mr-2">account_circle</span> Edit Account
                            </router-link>
<!--                            <router-link class="dropdown-item d-flex align-items-center py-2" to="/setting">-->
<!--                                <span class="material-icons mr-2">settings</span> Settings-->
<!--                            </router-link>-->
                            <span
                                @click.prevent="logout"
                                style="cursor: pointer;"
                                class="dropdown-item d-flex align-items-center py-2">
                                <span class="material-icons mr-2">exit_to_app</span> Logout
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {getAuthUser} from "../../init-app";

    export default {
        name: "TopNav",
        props: {
            initUser: false
        },
        data() {
            return {
                authUser: getAuthUser()
            };
        },
        computed: {
            appSetting: function f() {
                return window.appSetting;
            },
            notifications() {
                return [0, 1, 2];
            }
        },
        mounted() {
            this.$root.$on(eventName.sessionActive, user => {
                this.authUser = user;
            });
        },
        methods: {
            logout(event) {
                event.preventDefault();
                localStorage.removeItem(appSetting.jwt);
                localStorage.removeItem(appSetting.id_tkn);
                location.href = "/login";
            }
        }
    };
</script>

<style scoped>
</style>
