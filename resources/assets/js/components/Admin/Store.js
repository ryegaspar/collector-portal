import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
	state: {
		client_lists: [],
		commission_structures: {},
		collector_groups: [],
		letter_request_types: [],
		sub_sites: [],
		sites: [],
		statuses: {},
		team_leaders: [],
	},

	mutations: {
		updateLetterRequestType(state, data) {
			state.letter_request_types = data.letter_request_types;
		},

		updateClientLists(state, data) {
			state.client_lists = data.client_lists;
		},

		updateSubsiteOptions(state, data) {
			state.collector_groups = data.collector_groups;
			state.sites = data.sites;
		},

		updateCollectorOptions(state, data) {
			state.letter_request_types = data.letter_request_types;
			state.commission_structures = data.commission_structures;
			state.team_leaders = data.team_leaders;
			state.statuses = data.statuses();
		}
	},

	actions: {
		loadLetterRequestType({commit}) {
			axios.get('/admin/active-letter-request-types')
				.then(({data}) => {
					commit('updateLetterRequestType', data);
				});
		},

		loadClientLists({commit}) {
			axios.get('/api/clients')
				.then(({data}) => {
					commit('updateClientLists', data)
				});
		},

		loadSubsiteOptions({commit}) {
			axios.get('/api/subsite-options')
				.then(({data}) => {
					commit('updateSubsiteOptions', data);
				});
		},

		loadCollectorOptions({commit}) {
			axios.get('/api/collector-options')
				.then(({data}) => {
					commit('updateCollectorOptions', data);
				});
		}
	}
})