
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
// import Form from 'vue-form-helper-preset-laravel';
import SweetAlert from 'sweetalert';
import * as lib from './utilities/Lib';

window.Form = require('vue-form-helper-preset-laravel');
Form.clear();
window.swal = SweetAlert;
window.lib = lib;

Vue.component('topnavbar', require('./components/Admin/TopNavBar'));
Vue.component('sidebar', require('./components/Admin/SideBar'));

Vue.component('login', require('./components/Admin/Login'));
Vue.component('profile', require('./components/Admin/Profile'));

Vue.component('dashboard', require('./components/Admin/Dashboard'));
Vue.component('collector-hours', require('./components/Admin/CollectorHoursDetail'));
Vue.component('todays-totals', require('./components/Admin/TodaysTotalsDetail'));
Vue.component('remittance-log', require('./components/Admin/RemittanceLog'));
Vue.component('correspondence-log', require('./components/Admin/CorrespondenceLog'));
Vue.component('operationalreports', require('./components/Admin/OperationalReports'));
Vue.component('adjustments', require('./components/Admin/Adjustments'));
Vue.component('letter-requests', require('./components/Admin/LetterRequests'));
Vue.component('desk-transfer-requests', require('./components/Admin/DeskTransferRequests'));
Vue.component('letter-request-types', require('./components/Admin/LetterRequestTypes'));
Vue.component('scripts', require('./components/Admin/Scripts'));
Vue.component('script-create-edit', require('./components/Admin/ScriptCreateEdit'));
Vue.component('sif-closures', require('./components/Admin/SifClosures'));
Vue.component('closed-accounts-pdc', require('./components/Admin/ClosedAccountsPdc'));
Vue.component('recalls', require('./components/Admin/Recalls'));
Vue.component('collectors', require('./components/Admin/Collectors'));
Vue.component('collector-batches', require('./components/Admin/CollectorBatches'));
Vue.component('collector-batch-lists', require('./components/Admin/CollectorBatchLists'));
Vue.component('admins', require('./components/Admin/Admins'));
Vue.component('roles-permissions', require('./components/Admin/RolesPermissions'));
Vue.component('sites', require('./components/Admin/Sites'));
Vue.component('sub-sites', require('./components/Admin/SubSites'));

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
		window.location.href = '/admin/login';
		return;
	}
	return Promise.reject(error);
});

// local storage to show swal
if (localStorage.getItem("swal") != null) {
	swal(JSON.parse(localStorage.getItem("swal")))
	localStorage.clear();
}
