
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
import Form from './utilities/Form';
import SweetAlert from 'sweetalert';
import * as lib from './utilities/Lib';

window.Form = Form;
window.swal = SweetAlert;
window.lib = lib;

Vue.component('topnavbar', require('./components/Collector/TopNavBar'));

Vue.component('sidebar', require('./components/Collector/SideBar'));
Vue.component('login', require('./components/Collector/Login'));
Vue.component('dashboard', require('./components/Collector/Dashboard'));
Vue.component('accounts', require('./components/Collector/Accounts'));
Vue.component('transactions', require('./components/Collector/Transactions'));
Vue.component('adjustments', require('./components/Collector/Adjustments'));
Vue.component('letter-requests', require('./components/Collector/LetterRequests'));
Vue.component('scripts', require('./components/Collector/Scripts'));

const app = new Vue({
    el: '#app'
});

require('./genesisui');

// intercept ajax if user is unauthenticated
window.axios.interceptors.response.use((response) => {
	// Check if the user is no longer signed in,
	// if so then we need them to sign back in.
	return response;
}, (error) => {
	if (error.response.status === 401 && error.response.data !== 'invalid user') {
		window.location.href = '/login';
		return;
	}
	return Promise.reject(error);
});
