import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
	state: {
		sub_sites: [],
		commission_structures: {},
		team_leaders: [],
		statuses: {}
	},

	mutations: {
		updateData(state, data) {
			state.sub_sites = data.sub_sites;
			state.commission_structures = data.commission_structures;
			state.team_leaders = data.team_leaders;
			state.statuses = data.statuses;
		}
	},

	actions: {
		loadData({commit}) {
			axios.get('/admin/collectors/collector-options')
				.then(({data}) => {
					commit('updateData', data);
				});
		}
	}
})