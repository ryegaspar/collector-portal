
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

window.Form = Form;
window.swal = SweetAlert;

Vue.component('topnavbar', require('./components/Admin/TopNavBar'));

Vue.component('sidebar', require('./components/Admin/SideBar'));
Vue.component('login', require('./components/Admin/Login'));
Vue.component('profile', require('./components/Admin/Profile'));
Vue.component('adjustments', require('./components/Admin/Adjustments'));
Vue.component('users', require('./components/Admin/Users'));
Vue.component('scripts', require('./components/Admin/Scripts'));
Vue.component('script-create-edit', require('./components/Admin/ScriptCreateEdit'));

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
