<template>
    <div class="modal fade" id="modalAdjustment" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-if="isAdd">Add Adjustment</h4>
                    <h4 class="modal-title" v-else>Edit Adjustment</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="" @keydown="form.errors.clear()">
                        <fieldset class="form-group">
                            <label>Debter No:</label>
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
                            <label>Payment Date:</label>
                            <div class="input-group">
                                <datepicker style="flex: 1 1 auto;"
                                            input-class="form-control text-right"
                                            v-model="form.date">
                                </datepicker>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('date')">
                                    {{ form.errors.get('date') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Payment Amount:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.amount">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('amount')"
                                    v-html="form.errors.get('amount')">
                                </em>
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
	import Datepicker from 'vuejs-datepicker';

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
					amount: '',
					date: '',
				})
			}
		},

        mounted() {
			this.$events.$on('modal-reset', eventData => this.onResetModal());
        },

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let notifyMessage = "Successfully added an adjustment";

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				this.form.post('/adjustments')
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#modalAdjustment").modal('hide');

						swal({
                            title: "Success",
                            text: notifyMessage,
                            icon: 'success',
                            timer: 1250
                        });

						this.$events.fire('reload-table');
						// this.$emit('reload');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;
					})

			},

            onResetModal() {
				this.form.errors.clear();
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