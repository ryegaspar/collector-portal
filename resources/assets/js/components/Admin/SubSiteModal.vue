<template>
    <div class="modal fade" id="subSiteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form @submit.prevent="submit" @keydown="form.errors.clear()">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="isAdd">Add Sub Site</h4>
                        <h4 class="modal-title" v-else>Edit Sub Site</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label>Name</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.name">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('name')">
                                            {{ form.errors.get('name') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Site</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.site_id"
                                                @change="form.errors.clear()">
                                            <option :value="site.id"
                                                    v-for="site in sites">
                                                {{ site.name }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('site_id')">
                                            {{ form.errors.get('site_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group row">
                                    <label class="col-md-6 col-form-label">Has Team Leaders</label>
                                    <div class="col-md-6 col-form-label">
                                        <div class="form-check checkbox">
                                            <input type="checkbox" class="form-check-input"
                                                   v-model="form.has_team_leaders">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Description:</label>
                                    <div class="input-group">
                                <textarea class="form-control"
                                          rows="5"
                                          style="resize:none"
                                          v-model="form.description">
                                </textarea>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label>Default Collector Group</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.default_collector_group"
                                                @change="form.errors.clear()">
                                            <option :value="collector_group.UGP_CODE"
                                                    v-for="collector_group in collector_groups">
                                                {{ collector_group.UGP_DESC }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('default_collector_group')">
                                            {{ form.errors.get('default_collector_group') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Minimum Desk Number</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control text-right"
                                               v-model="form.min_desk_number">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('min_desk_number')">
                                            {{ form.errors.get('min_desk_number') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Maximum Desk Number</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control text-right"
                                               v-model="form.max_desk_number">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('max_desk_number')">
                                            {{ form.errors.get('max_desk_number') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label class="input-group">CollectOne ID Assignment Method</label>
                                    <div class="input">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" value="1"
                                                   v-model="form.collectone_id_assignment_method">
                                            <label class="form-check-label">Use Initials + Number (e.g. cd1)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" value="2"
                                                   v-model="form.collectone_id_assignment_method">
                                            <label class="form-check-label">Use Fix Prefix + Number (e.g. p00)</label>
                                        </div>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('collectone_id_assignment_method')">
                                            {{ form.errors.get('collectone_id_assignment_method') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group row" v-if="showPrefixes">
                                    <label class="col-md-3 col-form-label">Prefixes</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="form.prefixes" multiple="true">
                                            <option :value="String.fromCharCode(96 + alphabet)" v-for="alphabet in 26">
                                                {{ String.fromCharCode(64 + alphabet)}}
                                            </option>
                                            <!--<option>A</option>-->
                                            <!--<option>B</option>-->
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button type="submit"
                                :class="this.isAdd ? 'btn btn-success' : 'btn btn-primary'"
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
	export default {
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
					name: '',
					site_id: '',
					has_team_leaders: false,
					description: '',
					default_collector_group: '',
					min_desk_number: '',
					max_desk_number: '',
					collectone_id_assignment_method: '',
					prefixes: [],
				}),

				updateID: '',
			}
		},

		// created() {
		// 	axios.get('/admin/sites?per_page=100')
		// 		.then(({data}) => {
		// 			data.data.forEach((element) => {
		// 				this.sites.push(element);
		// 			});
		// 		})
		// 		.catch((error) => {
		// 			lib.swalError(error.message);
		// 		});
		// },

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let url = '/admin/sub-sites';
				let notifyMessage = "Successfully added site";

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated sub site";
					action = 'patch';
					url = `/admin/sub-sites/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#subSiteModal").modal('hide');
						lib.swalSuccess(notifyMessage);
						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;
					})

			},

			resetModal() {
				this.form.reset();
				this.form.has_team_leaders = false;
				this.form.prefixes = [];
			},

			populateData(data) {
				this.form.reset();
				this.form.name = data.name;
				this.form.site_id = data.site_id;
				this.form.description = data.description;
				this.form.default_collector_group = data.default_collector_group;
				this.form.has_team_leaders = !!+data.has_team_leaders;
				this.form.min_desk_number = data.min_desk_number;
				this.form.max_desk_number = data.max_desk_number;
				this.form.collectone_id_assignment_method = data.collectone_id_assignment_method;
				if (data.prefixes) {
					this.form.prefixes = data.prefixes.split(",");
				}

				this.updateID = data.id;
			},
		},

		computed: {
			showPrefixes() {
				return +this.form.collectone_id_assignment_method === 2;
			},

			collector_groups() {
				return this.$store.state.collector_groups;
			},

            sites() {
				return this.$store.state.sites;
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
		},
	}
</script>