<template>
    <div class="modal fade" id="modalCollectorCreate" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-if="isAdd">Add Collector</h4>
                    <h4 class="modal-title" v-else>Edit Collector</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="" @keydown="form.errors.clear()">
                        <fieldset class="form-group">
                            <label>Category</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.category"
                                        @change="form.errors.clear()">
                                    <option :value="categories.indexOf(category)"
                                            v-for="category in categories">
                                        {{ category }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('category')">
                                    {{ form.errors.get('category') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group" v-if="form.category === 0">
                            <label>User ID:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.tiger_user_id">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('tiger_user_id')">
                                    {{ form.errors.get('tiger_user_id') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group" v-if="form.category === 0">
                            <label>Desk:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.desk">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('desk')">
                                    {{ form.errors.get('desk') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>First Name:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.first_name">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('first_name')">
                                    {{ form.errors.get('first_name') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Last Name:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.last_name">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('last_name')">
                                    {{ form.errors.get('last_name') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Hire Date:</label>
                            <div class="input-group">
                                <datepicker style="flex: 1 1 auto;"
                                            input-class="form-control text-right"
                                            v-model="form.start_date">
                                </datepicker>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('start_date')">
                                    {{ form.errors.get('start_date') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Manager</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.manager_id"
                                        @change="form.errors.clear()">
                                    <option :value="manager.id"
                                            v-for="manager in managers">
                                        {{ manager.full_name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('manager_id')">
                                    {{ form.errors.get('manager_id') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group" v-if="form.category !== 0">
                            <label>Team Leader</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.team_leader_id"
                                        @change="form.errors.clear()">
                                    <option :value="team_leader.id"
                                            v-for="team_leader in team_leaders">
                                        {{ team_leader.full_name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('team_leader_id')">
                                    {{ form.errors.get('team_leader_id') }}
                                </em>
                            </div>
                        </fieldset>
                        <em style="font-size: 12px;" v-if="form.category === 0">
                            for US collectors, Add User ID and Desk in CollectOne first, then enter them in the fields above.
                        </em>
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button :class="this.isAdd ? 'btn btn-success' : 'btn btn-primary'"
                            @click="submit"
                            :disabled="isLoading || form.errors.any()"
                            v-html="persistButtonText">
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import Datepicker from 'vuejs-datepicker';

	export default {
		components: {
			Datepicker
		},
		props: [
			'isAdd',
			'formData'
		],

		data() {
			return {
				persistButtonClass: 'btn btn-success',
				persistButtonText: 'Add',
				isLoading: false,

				form: new Form({
                    department: '',
					tiger_user_id: '',
					desk: '',
					last_name: '',
					first_name: '',
                    start_date: '',
					team_leader_id: '',
                    manager_id: '',
				}),

                sites: [],

			}
		},

		created() {
			axios.get('/admin/collectors/leaders')
				.then(({data}) => {
					data.team_leaders.forEach((element) => {
						this.team_leaders.push(element);
					});

					data.managers.forEach((element) => {
						this.managers.push(element);
                    });
				})
				.catch((error) => {
					lib.swalError(error.message);
				});
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let url = '/admin/collectors';
				let notifyMessage = "Successfully added collector";

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated user";
					action = 'patch';
					url = `/admin/collectors/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#modalCollectorCreate").modal('hide');
						lib.swalSuccess(notifyMessage);
						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						if (!this.isAdd) {
							lib.swalError(error.message);
						}
					})
			},
        //
			resetModal() {
				this.form.errors.clear();
				this.form.reset();
			},
        //
		// 	populateData(data) {
		// 		_.assign(this.form, data);
		// 		if (data.roles.length >= 1)
		// 			this.form.access_level = data.roles[0].name;
		// 		this.updateID = data.id;
		// 	}
		},
        //
		// watch: {
		// 	'isAdd': function (newVal, oldVal) {
		// 		if (newVal) {
		// 			this.persistButtonText = 'Add';
		// 		}
		// 		else {
		// 			this.persistButtonText = 'Update';
		// 		}
		// 	},
		// },
	}
</script>