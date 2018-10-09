<template>
    <div class="modal fade" id="letterRequestTypeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-if="isAdd">Add Letter Request Type</h4>
                    <h4 class="modal-title" v-else>Edit Letter Request Type</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="submit" @keydown="form.errors.clear()">
                        <div class="row">
                            <div class="col-md-12">
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
                                        <textarea class="form-control" v-model="form.description"></textarea>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button type="submit"
                            :class="this.isAdd ? 'btn btn-success' : 'btn btn-primary'"
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
					description: '',
				}),

				updateID: '',
			}
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let url = '/admin/letter-request-type';
				let notifyMessage = "Successfully added Letter Request Type";

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated Letter Request Type";
					action = 'patch';
					url = `/admin/letter-request-type/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#letterRequestTypeModal").modal('hide');
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
			},

			populateData(data) {
				this.form.reset();
				this.form.name = data.name;
				this.form.description = data.description;

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