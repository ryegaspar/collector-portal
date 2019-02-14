<template>
    <div class="modal fade" id="deskTransferRequestModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form @submit.prevent="submit" @keydown="form.errors.clear()">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="isAdd">New Desk Transfer Request</h4>
                        <h4 class="modal-title" v-else>Edit Desk Transfer Request</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <fieldset class="form-group">
                            <label>Account No:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       maxlength="10"
                                       v-model="form.dbr_no">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('dbr_no')">
                                    {{ form.errors.get('dbr_no') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Transfer to Desk:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       maxlength="3"
                                       :value="desk"
                                       disabled>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('desk')">
                                    {{ form.errors.get('desk') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Request Reason</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.request_reason"
                                        @change="form.errors.clear()">
                                    <option :value="request_reason.id"
                                            v-for="request_reason in request_reasons">
                                        {{ request_reason.name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('request_reason')">
                                    {{ form.errors.get('request_reason') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Notes</label>
                            <div class="input-group">
                                <textarea class="form-control" v-model="form.notes" rows="5"></textarea>
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
	import Datepicker from 'vuejs-datepicker';
	import * as DeskTransferRequestOptions from '../../utilities/DeskTransferRequestOptions';

	export default {
		props: [
			'isAdd'
		],

		components: {
			Datepicker
		},

		data() {
			return {
				persistButtonClass: 'btn btn-success',
				persistButtonText: 'Add',
				isLoading: false,

				form: new Form({
					dbr_no: '',
					request_reason: '',
					notes: '',
                    desk: this.desk,
				}),

				updateID: '',

				request_reasons: DeskTransferRequestOptions.request_reasons
			}
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let notifyMessage = "Successfully added desk transfer request";
				let url = '/desk-transfer-requests';

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated desk transfer request";
					action = 'patch';
					url = `/desk-transfer-requests/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#deskTransferRequestModal").modal('hide');
						lib.swalSuccess(notifyMessage);

						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						if (!this.isAdd && error.status !== 422) {
							$("#deskTransferRequestModal").modal('hide');
							lib.swalError(error.statusText);
						}
					});
			},

			populateData(data) {
				this.form.reset();
				_.assign(this.form, data);
				this.updateID = data.id;
			},

			resetModal() {
				this.form.errors.clear();
				this.form.reset();
			},
		},

		computed: {
			desk() {
				return window.App.desk;
            }
		},

		watch: {
			'isAdd': function (newVal, oldVal) {
				if (newVal)
					this.persistButtonText = 'Add';
				else
					this.persistButtonText = 'Update';
			},
		},
	}
</script>