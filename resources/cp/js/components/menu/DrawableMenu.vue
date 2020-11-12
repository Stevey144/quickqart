<template>
    <div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
        <div class="mdk-drawer__content">
            <div class="sidebar sidebar-light sidebar-left simplebar bg-white" data-simplebar>
                <div class="sidebar-block p-0 m-0">
                    <div class="d-flex align-items-center sidebar-p-a border-bottom bg-light">
                        <router-link  to="/profile/me" class="flex d-flex align-items-center text-body text-underline-0">
                            <span class="avatar avatar-sm mr-2">
                                <span class="avatar-title rounded-circle bg-soft-secondary text-muted">{{authUser.name.substr(0, 1)}}</span>
                            </span>
                            <span class="flex d-flex flex-column">
                                <strong>{{authUser.name}}</strong>
                                <small class="text-muted text-uppercase">{{authUser.role.join(', ')}}</small>
                            </span>
                        </router-link>
                        <div class="dropdown ml-auto">
                            <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <router-link class="dropdown-item" to="/">Dashboard</router-link>
                                <router-link class="dropdown-item" to="/profile/me">Edit account</router-link>
                                <div class="dropdown-divider"></div>
                                <a @click.prevent="logout" class="dropdown-item" rel="nofollow" data-method="delete" href="#">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-block p-0">

                    <div class="sidebar-heading">Menu</div>

                    <ul class="sidebar-menu mt-0">
                        <li v-for="m in menu" class="sidebar-menu-item">
                            <router-link class="sidebar-menu-button" :to="m.url">
                                <span class="sidebar-menu-icon sidebar-menu-icon--left">
                                    <span class="material-icons">{{m.icon}}</span>
                                </span>
                                <span class="sidebar-menu-text">{{m.name}}</span>
                            </router-link>
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
        name: "DrawableMenu",
        computed: {
            menu() {
                return [
                    {name: 'Dashboard', url: '/', icon: 'home'},
                    {name: 'Stores', url: '/stores', icon: 'person'},
                    {name: 'Users', url: '/management', icon: 'people'},
                    {name: 'Settings', url: '/profile/me', icon: 'settings'},
                    {name: 'FAQs', url: '/faqs', icon: 'help'},
                ];
            },
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
    @import "../../../sass/variables";
    .sidebar-light .open > .sidebar-menu-button .sidebar-menu-icon {
        color: rgba(0, 0, 0, 0.54);
    }

    .sidebar-light .active > .sidebar-menu-button {
        color: #212121;
    }

    .sidebar-light .active > .sidebar-menu-button .sidebar-menu-icon {
        color: $primary-color;
    }

    .sidebar-light .sidebar-submenu .sidebar-menu-button {
        color: rgba(0, 0, 0, 0.54);
    }

    .sidebar-light .sidebar-submenu .sidebar-menu-icon {
        color: rgba(0, 0, 0, 0.54);
    }

</style>
