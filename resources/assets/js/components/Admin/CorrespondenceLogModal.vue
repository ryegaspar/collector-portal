<template>
    <div class="modal fade" id="correspondenceLogModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form @submit.prevent="submit" @keydown="form.errors.clear()">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="isAdd">New Correspondence Entry</h4>
                        <h4 class="modal-title" v-else>Edit Correspondence Entry</h4>
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
                                       v-model="form.account_no">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('account_no')">
                                    {{ form.errors.get('account_no') }}
                                </em>
                            </div>
                        </fieldset>
						<fieldset class="form-group">
                            <label>Correspondence Sent From:</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.correspondence_from"
                                        @change="form.errors.clear()">
                                    <option :value="correspondence.name"
                                            v-for="correspondence in correspondence_from">
                                        {{ correspondence.name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('correspondence_from')">
                                    {{ form.errors.get('correspondence_from') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Correspondence Type:</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.correspondence_type"
                                        @change="form.errors.clear()">
                                    <option :value="correspondence.name"
                                            v-for="correspondence in correspondence_type">
                                        {{ correspondence.name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('correspondence_type')">
                                    {{ form.errors.get('correspondence_type') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Correspondence Date:</label>
                            <div class="input-group">
                                <datepicker style="flex: 1 1 auto;"
                                            input-class="form-control text-right"
                                            v-model="form.correspondence_date">
                                </datepicker>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('correspondence_date')">
                                    {{ form.errors.get('correspondence_date') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Department:</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.assigned_department"
                                        @change="form.errors.clear()">
                                    <option :value="department.name"
                                            v-for="department in assigned_department">
                                        {{ department.name }}
                                    </option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('assigned_department')">
                                    {{ form.errors.get('assigned_department') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Attach Correspondence</label>
                            <div class="input-group">
                                <input type="file"
                                       @change="fileChanged"
                                       ref="fileUpload">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('attachment')">
                                    {{ form.errors.get('attachment') }}
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
    import * as CorrespondenceLogOptions from '../../utilities/CorrespondenceLogOptions';

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

                dropOptions: {
					url: "/files"
				},

				form: new Form({
                    account_no: '',
                    correspondence_from: '',
                    correspondence_type: '',
                    correspondence_date: '',
                    assigned_department: '',
                    attachment: '',
                    notes: '',
				}),

                updateID: '',
                
                correspondence_from: CorrespondenceLogOptions.correspondence_from, 
                correspondence_type: CorrespondenceLogOptions.correspondence_type,
                assigned_department: CorrespondenceLogOptions.assigned_department,

			}
		},

		methods: {
            fileChanged(e) {
				if (!e.target.files.length) return;

				this.form.errors.clear();

				this.form.attachment = e.target.files[0];
			},
			submit() {
				let tempButtonText = this.persistButtonText;

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				let formData = new FormData();

				formData.append('attachment', this.form.attachment);
                formData.append('account_no', this.form.account_no);
                formData.append('correspondence_from', this.form.correspondence_from);
                formData.append('correspondence_type', this.form.correspondence_type);
                formData.append('assigned_department', this.form.assigned_department);
                formData.append('notes', this.form.notes);
				formData.append('correspondence_date', new Date(this.form.correspondence_date).toUTCString());

				axios.post('/admin/correspondence-log', formData,
					{
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					})
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#correspondenceLogModal").modal('hide');
						lib.swalSuccess("Successfully added correspondence entry");

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

			populateData(data) {
				this.form.reset();
				_.assign(this.form, data);
				this.updateID = data.id;
			},

            resetModal() {
				this.form.errors.clear();
				this.form.reset();

				const input = this.$refs.fileUpload;
				input.type = 'text';
				input.type = 'file';
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