<template>
    <div class="modal fade" id="letterRequestModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-if="isAdd">New Letter Request</h4>
                    <h4 class="modal-title" v-else>Edit Letter Request</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="submit" @keydown="form.errors.clear()">
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
                            <label>Request Method</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.request_method"
                                        @change="form.errors.clear()">
                                    <option :value="request_method.id"
                                            v-for="request_method in request_methods">
                                        {{ request_method.name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('request_method')">
                                    {{ form.errors.get('request_method') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Request Type</label>
                            <div class="input-group">
                                <select class="form-control"
                                        @change="form.errors.clear()"
                                        v-model="form.type">
                                    <option :value="type.id"
                                            v-for="type in letter_request_types">
                                        {{ type.name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('type')">
                                    {{ form.errors.get('type') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Borr / Co-Borrower</label>
                            <div class="input-group">
                                <select class="form-control"
                                        @change="form.errors.clear()"
                                        v-model="form.borrower_type">
                                    <option :value="type.id"
                                            v-for="type in borrower_types">
                                        {{ type.name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('borrower_type')">
                                    {{ form.errors.get('borrower_type') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Notes</label>
                            <div class="input-group">
                                <textarea class="form-control" v-model="form.notes" rows="5"></textarea>
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
	import * as LetterRequestOptions from '../../utilities/LetterRequestOptions';

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
					request_method: '',
					type: '',
                    borrower_type: '',
					notes: '',
				}),

				updateID: '',

                request_methods: LetterRequestOptions.request_methods,

                borrower_types: LetterRequestOptions.borrower_types
			}
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let notifyMessage = "Successfully added letter request";
				let url = '/letter-requests';

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated letter request";
					action = 'patch';
					url = `/letter-requests/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#letterRequestModal").modal('hide');
						lib.swalSuccess(notifyMessage);

						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						if (!this.isAdd && error.status !== 422) {
							$("#letterRequestModal").modal('hide');
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
			letter_request_types() {
				return this.$store.state.letter_request_types;
			},
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