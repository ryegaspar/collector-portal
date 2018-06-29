
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('topnavbar', require('./components/TopNavBar'));

Vue.component('sidebar', require('./components/Users/SideBar'));
Vue.component('login', require('./components/Users/Login'));
Vue.component('dashboard', require('./components/Users/Dashboard'));
Vue.component('accounts', require('./components/Users/Accounts'));
Vue.component('transactions', require('./components/Users/Transactions'));

const app = new Vue({
    el: '#app'
});

require('./genesisui');

