<template>
    <div class="modal fade" id="collectorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form @submit.prevent="submit" @keydown="form.errors.clear()">

                    <div class="modal-header">
                        <h4 class="modal-title" v-if="isAdd">Add Collector</h4>
                        <h4 class="modal-title" v-else>Edit Collector</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label>Sub Site</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.sub_site_id"
                                                @change="onSubSiteChange(form.sub_site_id)"
                                                :disabled="!isAdd">
                                            <option :value="sub_site.id"
                                                    v-for="sub_site in sub_sites">
                                                {{ sub_site.name }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('sub_site_id')">
                                            {{ form.errors.get('sub_site_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group" v-if="!isAdd">
                                    <label>Username</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control text-right"
                                               v-model="form.username"
                                               disabled>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>First Name</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.first_name">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('first_name')">
                                            {{ form.errors.get('first_name') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Last Name</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.last_name">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('last_name')">
                                            {{ form.errors.get('last_name') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Hire Date</label>
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
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group" v-if="hasTeamLeader">
                                    <label>Team Leader</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.team_leader_id"
                                                @change="form.errors.clear()">
                                            <option :value="team_leader.id"
                                                    v-for="team_leader in team_leaders_options">
                                                {{ team_leader.full_name }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('team_leader_id')">
                                            {{ form.errors.get('team_leader_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset v-if="!isAdd">
                                    <label>Group Designation</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.group"
                                                @change="form.errors.clear()">
                                            <option :value="group.UGP_CODE"
                                                    v-for="group in collector_groups">
                                                {{ group.UGP_DESC }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('group')">
                                            {{ form.errors.get('group') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Commission Structure</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.commission_structure_id">
                                            <option :value="index"
                                                    v-for="(commission_structure, index) in commission_structures">
                                                {{ commission_structure }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('commission_structure_id')">
                                            {{ form.errors.get('commission_structure_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Collector Status</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.status_id">
                                            <option :value="index"
                                                    v-for="(status, index) in statuses">
                                                {{ status }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('status_id')">
                                            {{ form.errors.get('status_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary"
                                type="button"
                                data-dismiss="modal">Close
                        </button>
                        <button :class="this.isAdd ? 'btn btn-success' : 'btn btn-primary'"
                                type="submit"
                                :disabled="isLoading || form.errors.any()"
                                v-html="persistButtonText">
                        </button>
                    </div>
                </form>
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
		],

		data() {
			return {
				updateID: '',
				persistButtonClass: 'btn btn-success',
				persistButtonText: 'Add',
				isLoading: false,

				form: new Form({
					sub_site_id: '',
					username: '',
					tiger_user_id: '',
					desk: '',
					last_name: '',
					first_name: '',
					start_date: '',
					team_leader_id: '',
                    group: '',
					commission_structure_id: '',
					status_id: ''
				}),

				team_leaders_options: [],

				hasTeamLeader: false,
			}
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

						$("#collectorModal").modal('hide');
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

			resetModal() {
				this.form.errors.clear();
				this.form.reset();
			},

			onSubSiteChange() {
				this.form.errors.clear();
			},

			populateData(data) {
				this.form.reset();
				_.assign(this.form, data);
				Vue.nextTick(() => {
					this.form.team_leader_id = data.team_leader_id;
				});
				this.updateID = data.id;
			}
		},

		computed: {
			sub_sites() {
				return this.$store.state.sub_sites;
			},

			commission_structures() {
				return this.$store.state.commission_structures;
			},

			team_leaders() {
				return this.$store.state.team_leaders;
			},

			statuses() {
				return this.$store.state.statuses;
			},

            collector_groups() {
				return this.$store.state.collector_groups;
            }
		},

		watch: {
			'isAdd': function (newVal, oldVal) {
				if (newVal) {
					this.persistButtonText = 'Add';
				} else {
					this.persistButtonText = 'Update';
				}
			},

			'form.sub_site_id': function (newVal, oldVal) {
				if (newVal) {
					let obj = this.sub_sites.find(o => +o.id === +newVal);
					this.hasTeamLeader = +obj.has_team_leaders;

					this.team_leaders_options = this.team_leaders.filter((t) => {
						return +t.sub_site_id === +newVal;
					});
				}
			}
		},
	}
</script>