<template>
    <div class="modal fade" id="deskTransferRequestRejectReasonModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Reason</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="submit" @keydown="form.errors.clear()">
                        <fieldset class="form-group">
                            <label>Why was it rejected?</label>
                            <div class="input-group">
                                <textarea class="form-control" v-model="form.reason" rows="5"></textarea>
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
	export default {
		props: ['rejectedId'],

		data() {
			return {
				persistButtonText: 'Ok',
				isLoading: false,

				form: new Form({
					reason: '',
				}),
			}
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'patch';
				let notifyMessage = "Successfully changed the status of desk transfer request";
				let url = `/admin/desk-transfer-requests/${this.rejectedId}/deny`;

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`;

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#deskTransferRequestRejectReasonModal").modal('hide');
						lib.swalSuccess(notifyMessage);

						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						if (error.status !== 422) {
							$("#deskTransferRequestRejectReasonModal").modal('hide');
							lib.swalError(error.message);
						}
					});
			},
		},
	}
</script>