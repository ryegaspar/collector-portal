<template>
    <div class="modal fade" id="remittanceLogModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form @submit.prevent="submit" @keydown="form.errors.clear()">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="isAdd">New Remittance Entry</h4>
                        <h4 class="modal-title" v-else>Edit Remittance Entry</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
						<fieldset class="form-group">
                            <label>Client Name</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.client_name"
                                        @change="form.errors.clear()">
                                    <option :value="client_name.client_name1"
                                            v-for="client_name in unifin_client_list">
                                        {{ client_name.client_name1 }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('client_name')">
                                    {{ form.errors.get('client_name') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Remit Date</label>
                            <div class="input-group">
                                <datepicker style="flex: 1 1 auto;"
                                            input-class="form-control text-right"
                                            v-model="form.remit_date">
                                </datepicker>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('remit_date')">
                                    {{ form.errors.get('remit_date') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Period Start Date</label>
                            <div class="input-group">
                                <datepicker style="flex: 1 1 auto;"
                                            input-class="form-control text-right"
                                            v-model="form.period_start_date">
                                </datepicker>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('period_start_date')">
                                    {{ form.errors.get('period_start_date') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Period End Date</label>
                            <div class="input-group">
                                <datepicker style="flex: 1 1 auto;"
                                            input-class="form-control text-right"
                                            v-model="form.period_end_date">
                                </datepicker>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('period_end_date')">
                                    {{ form.errors.get('period_end_date') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Total Collections</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       maxlength="10"
                                       v-model="form.total_collections">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('total_collections')">
                                    {{ form.errors.get('total_collections') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Total Direct Payments</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       maxlength="10"
                                       v-model="form.total_client_collections">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('total_client_collections')">
                                    {{ form.errors.get('total_client_collections') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Commission Amount</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       maxlength="10"
                                       v-model="form.commission_amount">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('commission_amount')">
                                    {{ form.errors.get('commission_amount') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Remittance Amount</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       maxlength="10"
                                       v-model="form.remit_amount">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('remit_amount')">
                                    {{ form.errors.get('remit_amount') }}
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
    import * as RemittanceLogOptions from '../../utilities/RemittanceLogOptions';
    import * as DeskTransferRequestOptions from '../../utilities/DeskTransferRequestOptions';

	export default {
		props: [
            'isAdd',
            'unifinclients'

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
                    client_name: '',
                    remit_date: '',
                    period_start_date: '',
                    period_end_date: '',
                    total_collections: '',
                    total_client_collections: '',
                    commission_amount: '',
                    remit_amount: '',
                    notes: '',
                    request_reason: '',
				}),

                updateID: '',
                
                unifin_client_list: this.unifinclients, 

			}
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let notifyMessage = "Successfully added remittance entry";
				let url = '/admin/remittance-log';

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated remittance entry";
					action = 'patch';
					url = `/admin/remittance-log/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#remittanceLogModal").modal('hide');
						lib.swalSuccess(notifyMessage);

						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						if (!this.isAdd && error.status !== 422) {
							$("#remittanceLogModal").modal('hide');
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