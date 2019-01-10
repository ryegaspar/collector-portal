import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
	state: {
		letter_request_types: [],
	},

	mutations: {
		updateLetterRequestType(state, data) {
			state.letter_request_types = data.letter_request_type;
		}
	},

	actions: {
		loadLetterRequestType({commit}) {
			axios.get('/api/active-letter-request-types')
				.then(({data}) => {
					commit('updateLetterRequestType', data);
				});
		}
	}
})