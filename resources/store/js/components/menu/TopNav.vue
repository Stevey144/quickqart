<template>
    <div id="header" class="mdk-header bg-dark js-mdk-header m-0" data-fixed data-effects="waterfall">
        <div class="mdk-header__content">
            <div
                class="navbar navbar-expand-sm navbar-main navbar-light bg-white pl-md-0 pr-0"
                id="navbar"
                data-light>
                <div class="container ">
                    <div class="d-flex sidebar-account flex-shrink-0">
                        <button class="navbar-toggler navbar-toggler-right" data-toggle="collapse" data-target="#secondaryNavbar" type="button">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <router-link to="/" class="mh-left-margin flex d-flex align-items-center text-underline-0">
                          <span class="mr-1 text-white">
                              <img class="d-none d-md-inline-block" src="/parent/assets/img/logo.png" height="42" style="opacity: 1!important"/>
                              <img class="d-inline-block d-md-none" src="/parent/assets/img/logo2.png" height="42" style="opacity: 1!important"/>
                          </span>
                        </router-link>
                    </div>

                    <ul class="nav navbar-nav d-md-flex ml-auto" style="padding-right: 15px; border-right: 2px solid #F3F3F3">
                        <li class="nav-item">
                            <select class="custom-select py-1" @change="storeChanged" v-model="currentStore">
                                <option v-for="store in stores" :value="store.slug">{{store.name}}</option>
                            </select>
                        </li>
                    </ul>

                    <div class="dropdown">
                        <a style="text-decoration: none!important;"
                            href="#account_menu"
                            class="dropdown-toggle border-left d-flex align-items-center ml-navbar"
                            data-toggle="dropdown">
                          <span class="avatar avatar-sm mr-2">
                            <span
                                class="avatar-title rounded-circle bg-primary text-white">{{authUser.name.substr(0, 1)}}</span>
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
                                to="/store-settings">
                                <span class="material-icons mr-2">settings</span> Edit Store
                            </router-link>
                            <router-link
                                class="dropdown-item d-flex align-items-center py-2"
                                to="/edit-details">
                                <span class="material-icons mr-2">settings</span> Advance
                            </router-link>
                            <router-link class="dropdown-item d-flex align-items-center py-2" to="/setting">
                                <span class="material-icons mr-2">account_circle</span> Edit Login Detail
                            </router-link>
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
                authUser: getAuthUser(),
                currentStore: '',
                stores: []
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
            this.currentStore = localStorage.getItem(appSetting.pref_store);
            this.updateStores();
            this.$root.$on(eventName.sessionActive, user => {
                this.authUser = user;
            });
        },
        methods: {
            updateStores() {
                const appStores = localStorage.getItem(appSetting.stores_tkn);
                let stores = [];
                if (appStores) {
                    try {
                        stores = JSON.parse(appStores);
                    } catch (e) {
                        stores = [];
                    }
                }

                this.stores = stores;
            },
            storeChanged() {
                localStorage.setItem(appSetting.pref_store, this.currentStore);
                this.updateStores();
                const currentStoreObj = this.stores.find(f => f.slug);
                let currency = '₦';

                if (currentStoreObj && currentStoreObj.currency && currentStoreObj.currency.code) {
                    currency = currentStoreObj.currency.code;
                }

                localStorage.setItem(appSetting.pref_currency, currency);

                if (this.$route.name === 'SingleStore'
                    // && this.$route.fullPath !== '/my-stores/new'
                ) {
                    location.href = '/';
                } else {
                    location.reload();
                }
            },
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
