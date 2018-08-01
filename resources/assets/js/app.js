
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuetify = require('vuetify');
Vue.use(Vuetify);

import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';


import VueRouter from 'vue-router';
Vue.use(VueRouter);

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('admin-component', require('./components/AdminComponent.vue'));
Vue.component('sliplist', require('./components/Admin/PaySlipComponent.vue'));

const router = new VueRouter({
  mode: 'history',
  routes: [
    { path: '/home',       name: 'home',       component: require('./components/HomeComponent.vue')},
    { path: '/admin/user', name: 'admin_user', component: require('./components/Admin/UserComponent.vue')},
    { path: '/admin/slip', name: 'admin_slip', component: require('./components/Admin/CsvSlipComponent.vue')},

    // catch all redirect
    { path: '*', redirect: '/home' }
  ]
});



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    router,
});
