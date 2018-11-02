import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
	state: {
		letter_request_types: [],
		client_lists: [],
	},

	mutations: {
		updateLetterRequestType(state, data) {
			state.letter_request_types = data.letter_request_type;
		},

		updateClientLists(state, data) {
			state.client_lists = data.client_lists;
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
		}
	}
})