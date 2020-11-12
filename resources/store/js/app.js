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
import Orders from "./views/orders/Orders";
import Products from "./views/products/Products";
import Categories from "./views/categories/Categories";
import AddEditSingleProduct from "./views/products/AddEditSingleProduct";
import ViewProductDetail from "./views/products/ViewProductDetail";
import SingleOrder from "./views/orders/SingleOrder";
import SpecialOffers from "./views/offers/SpecialOffers";
import AddEditSingleOffer from "./views/offers/AddEditSingleOffer";
import Setting from "./views/settings/Setting";
import Inventory from "./views/products/Inventory";
import Stores from "./views/stores/Stores";
import AddEditStore from "./views/stores/AddEditStore";

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
        path: '/orders',
        name: 'Orders',
        component: Orders,
        meta: {protected: true}
    }, {
        path: '/orders/:orderId',
        name: 'SingleOrder',
        component: SingleOrder,
        meta: {protected: true}
    }, {
        path: '/products',
        name: 'Products',
        component: Products,
        meta: {protected: true}
    }, {
        path: '/products/:slug',
        name: 'AddEditProduct',
        component: AddEditSingleProduct,
        meta: {protected: true}
    }, {
        path: '/special-offers',
        name: 'SpecialOffers',
        component: SpecialOffers,
        meta: {protected: true}
    }, {
        path: '/special-offers/:slug',
        name: 'AddEditSpecialOffer',
        component: AddEditSingleOffer,
        meta: {protected: true}
    }, {
        path: '/products/:slug/view',
        name: 'ViewProduct',
        component: ViewProductDetail,
        meta: {protected: true}
    }, {
        path: '/categories',
        name: 'Categories',
        component: Categories,
        meta: {protected: true}
    }, {
        path: '/my-stores/:storeId',
        name: 'SingleStore',
        component: AddEditStore,
        meta: {protected: true}
    }, {
        path: '/store-settings',
        redirect: '/my-stores/' + localStorage.getItem(appSetting.pref_store)
    }, {
        path: '/setting',
        name: 'Setting',
        component: Setting,
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
            m: 60,
            s: 1
        };
        let counter;
        // tslint:disable-next-line:forin
        for (const i in intervals) {
            counter = Math.floor(seconds / intervals[i]);
            if (counter > 0) {
                return counter + ' ' + i + ' ago';
            }
        }
    }
    return value;
});

Vue.filter('file_size', function (value) {
    if (value) {
        const bytes = value;
        const units = {
            PB: 1e15,
            TB: 1e12,
            GB: 1e9,
            MB: 1e6,
            KB: 1000,
            B: 1
        };
        let counter;
        // tslint:disable-next-line:forin
        for (const i in units) {
            counter = Math.floor(bytes / units[i]);
            if (counter > 0) {
                return counter + ' ' + i;
            }
        }
    }
    return value;
});
Vue.filter('currency', function (value, unit) {
    return bee.formatAsCurrency(value, unit)
});
Vue.filter('preview', function (value , length = 100) {
    if (!value) {
        return '';
    }
    if (value.length <= length) {
        return value;
    }
    return value.substr(0, length) + '...';
});



Vue.component("default-layout", MainLayout);
Vue.component('no-layout', NoLayout);
Vue.component('auth-layout', AuthLayout);
Vue.component('app-root', App);

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

