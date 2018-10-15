<template>
    <div class="modal fade" id="siteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form @submit.prevent="submit" @keydown="form.errors.clear()">

                    <div class="modal-header">
                        <h4 class="modal-title" v-if="isAdd">Add Site</h4>
                        <h4 class="modal-title" v-else>Edit Site</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
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
                            <label>Description</label>
                            <div class="input-group">
                                <textarea class="form-control"
                                          rows="5"
                                          style="resize:none"
                                          v-model="form.description">
                                </textarea>
                            </div>
                        </fieldset>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
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
					description: '',
				}),

				updateID: '',
			}
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let url = '/admin/sites';
				let notifyMessage = "Successfully added site";

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated site";
					action = 'patch';
					url = `/admin/sites/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#siteModal").modal('hide');
						lib.swalSuccess(notifyMessage);
						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;
					})

			},

			resetModal() {
				this.form.errors.clear();
				this.form.reset();
			},

			populateData(data) {
				this.form.reset();
				_.assign(this.form, data);
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