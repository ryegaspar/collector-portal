<template>
    <div class="modal fade" id="collectorBatchesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Batch</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="" @keydown="form.errors.clear()">
                        <fieldset class="form-group">
                            <label>CSV File</label>
                            <div class="input-group">
                                <input type="file"
                                       @change="fileChanged"
                                       ref="fileUpload">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('csv_file')">
                                    {{ form.errors.get('csv_file') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Name</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.name">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('name')">
                                    {{ form.errors.get('name') }}
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
                        <fieldset class="form-group">
                            <label>Sub Site</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.sub_site_id"
                                        @change="onSubSiteChange(form.sub_site_id)">
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
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary"
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
	import Store from './Store';

	export default {

		store: Store,

		components: {
			Datepicker,
		},

		data() {
			return {
				persistButtonClass: 'btn btn-success',
				persistButtonText: 'Make New',
				isLoading: false,

				dropOptions: {
					url: "/files"
				},

				form: new Form({
					csv_file: '',
					name: '',
					sub_site_id: '',
					start_date: '',
					team_leader_id: '',
					commission_structure_id: ''
				}),

				team_leaders_options: [],
			}
		},

		beforeCreate() {
			this.$store.dispatch('loadCollectorOptions');
		},

		// created() {
		// 	axios.get('/admin/collectors/collector-options')
		// 		.then(({data}) => {
		// 			_.assign(this.sub_sites, data.sub_sites);
        //
		// 			_.assign(this.commission_structures, data.commission_structures);
        //
		// 			_.assign(this.team_leaders, data.team_leaders);
		// 		})
		// 		.catch((error) => {
		// 			lib.swalError(error.message);
		// 		});
		// },

		methods: {
			fileChanged(e) {
				if (!e.target.files.length) return;

				this.form.errors.clear();

				this.form.csv_file = e.target.files[0];
			},

			submit() {
				let tempButtonText = this.persistButtonText;

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				let formData = new FormData();

				formData.append('csv_file', this.form.csv_file);
				formData.append('name', this.form.name);
				formData.append('start_date', new Date(this.form.start_date).toUTCString());
				formData.append('sub_site_id', this.form.sub_site_id);
				formData.append('team_leader_id', this.form.team_leader_id);
				formData.append('commission_structure_id', this.form.commission_structure_id);

				axios.post('/admin/collector-batches', formData,
					{
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#collectorBatchesModal").modal('hide');
						lib.swalSuccess("Successfully added batch of collectors");

						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						if (error.response.status == 422) {
							this.form.onFail(error.response);
						} else {
							lib.swalError(error.message);
						}
					})
			},

			resetModal() {
				this.form.errors.clear();
				this.form.reset();

				const input = this.$refs.fileUpload;
				input.type = 'text';
				input.type = 'file';
			},

			onSubSiteChange(subsite_id) {
				this.team_leaders_options = this.team_leaders.filter((t) => {
					return +t.sub_site_id === +subsite_id;
				});
				this.form.errors.clear();
			}
		},

		computed: {
			hasTeamLeader() {
				if (!!this.form.sub_site_id) {
					let obj = this.sub_sites.find(o => o.id === this.form.sub_site_id);
					return +obj.has_team_leaders;
				}
			},

			sub_sites() {
				return this.$store.state.sub_sites;
			},

			commission_structures() {
				return this.$store.state.commission_structures;
			},

			team_leaders() {
				return this.$store.state.team_leaders;
			},
		},
	}
</script>