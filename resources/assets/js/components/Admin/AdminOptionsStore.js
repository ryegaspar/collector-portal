import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
	state: {
		accessGroups: [],
		sites: [],
	},

	mutations: {
		updateData(state, data) {
			state.accessGroups = data.roles;
			state.sites = data.sites;
		}
	},

	actions: {
		loadData({commit}) {
			axios.get('/admin/admins/admin-options')
				.then(({data}) => {
					commit('updateData', data);
				});
		}
	}
})