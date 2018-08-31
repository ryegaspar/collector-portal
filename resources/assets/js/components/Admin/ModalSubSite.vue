<template>
    <div class="modal fade" id="modalSubSite" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-if="isAdd">Add Sub Site</h4>
                    <h4 class="modal-title" v-else>Edit Sub Site</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="" @keydown="form.errors.clear()">
                        <fieldset class="form-group">
                            <label>Name:</label>
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
				}),

                sites: [],

				updateID: '',
			}
		},

		created() {
			axios.get('/admin/sites?per_page=100')
				.then(({data}) => {
					data.data.forEach((element) => {
						this.sites.push(element);
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

						$("#modalSubSite").modal('hide');
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
			},

			populateData(data) {
				this.form.reset();
				this.form.name = data.name;
				this.form.site_id = data.site_id;
				this.form.description = data.description;
				this.form.has_team_leaders = !!+data.has_team_leaders;
				this.updateID = data.id;
			},
		},

		watch: {
			'isAdd': function (newVal, oldVal) {
				if (newVal) {
					this.persistButtonText = 'Add';
				}
				else {
					this.persistButtonText = 'Update';
				}
			},
		},
	}
</script>