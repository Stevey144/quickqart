import {canAccessCurrentRoute} from "./init-app";
import Vue from 'vue';
import App from './views/App';
import VueRouter from 'vue-router';
import VueJWT from 'vuejs-jwt';
import VueFlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import NotFound from "./views/NotFound";
import VerifyToken from "./views/auth/VerifyToken";
import Login from "./views/auth/Login";
import ForgotPassword from "./views/auth/ForgotPassword";
import ResetPassword from "./views/auth/ResetPassword";
import MainLayout from "./layouts/MainLayout";
import NoLayout from "./layouts/NoLayout";
import AuthLayout from "./layouts/AuthLayout";
import Dashboard from "./views/Dashboard";
import Stores from "./views/stores/Stores";
import Management from "./views/users/Management";
import AddEditUser from "./views/users/AddEditUser";
import Faqs from "./views/faqs/Faqs";

require('./bootstrap');


Vue.use(VueJWT, {
    keyName: appSetting.jwt
});
Vue.use(VueRouter);
Vue.use(VueFlatPickr);


const router = new VueRouter({
    mode: 'history',
    linkActiveClass: "",
    linkExactActiveClass: "exact-active active",
    routes: [{
        path: '/',
        name: 'home',
        component: Dashboard,
        meta: {
            protected: true
        }
    }, {
        path: '/auth/:token',
        name: 'AuthenticateToken',
        component: VerifyToken,
        meta: {
            layout: 'auth',
            protected: false
        }
    }, {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {
            layout: 'auth',
            protected: false
        }
    }, {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPassword,
        meta: {layout: 'auth', protected: false}
    }, {
        path: '/reset-password/:token',
        name: 'ResetPassword',
        component: ResetPassword,
        meta: {layout: 'auth', protected: false}
    }, {
        path: '/stores',
        name: 'Stores',
        component: Stores,
        meta: {protected: true}
    }, {
        path: '/faqs',
        name: 'FAQs',
        component: Faqs,
        meta: {protected: true}
    }, {
        path: '/stores/:slug',
        name: 'SingleStore',
        component: Stores,
        meta: {protected: true}
    }, {
        path: '/management',
        name: 'Users',
        component: Management,
        meta: {protected: true}
    }, {
        path: '/management/:username',
        name: 'UserSingle',
        component: AddEditUser,
        meta: {protected: true}
    }, {
        path: '/profile/:username',
        name: 'Profile',
        component: AddEditUser,
        meta: {protected: true}
    }, {
        path: '*',
        name: 'not-found',
        meta: {
            layout: 'no',
            title: "404 - Page not Found"
        },
        component: NotFound
    }],
});

Vue.filter('timeago', function (value) {
    if (value) {
        const seconds = Math.floor((+new Date() - +new Date(value)) / 1000);
        if (seconds < 29) {
            return 'Just now';
        }
        const intervals = {
            yr: 31536000,
            mon: 2592000,
            w: 604800,
            d: 86400,
            hr: 3600,
            min: 60,
            sec: 1
        };
        let counter;
        // tslint:disable-next-line:forin
        for (const i in intervals) {
            counter = Math.floor(seconds / intervals[i]);
            if (counter > 0) {
                return counter + ' ' + i + (counter > 1 ? 's' : '') + ' ago';
            }
        }
    }
    return value;
});

Vue.component("default-layout", MainLayout);
Vue.component('no-layout', NoLayout);
Vue.component('auth-layout', AuthLayout);
Vue.component('app-root', App);

Vue.filter('preview', function (value , length = 100) {
    if (!value) {
        return '';
    }
    if (value.length <= length) {
        return value;
    }
    return value.substr(0, length) + '...';
});

router.beforeEach((to, from, next) => {
    const resp = canAccessCurrentRoute(to);
    if (resp === 0) {
        next({name: 'Login'});
    } else if (resp === -1) next({name: 'AccessDenied'});
    else next();
});

Vue.config.productionTip = false;

new Vue({
    el: '#app',
    router,
    template: '<app></app>',
    components: {App},
});

