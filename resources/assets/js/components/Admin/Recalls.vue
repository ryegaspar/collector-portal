<template>
    <div class="animated fadeIn">
        <form @submit.prevent="save" @keydown="form.errors.clear()">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Recall</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Client</label>
                                        <div class="input-group">
                                            <select class="form-control"
                                                    @change="form.errors.clear()"
                                                    v-model="form.client">
                                                <option :value="key"
                                                        v-for="(client, key) in client_lists">
                                                    {{ client }}
                                                </option>
                                            </select>
                                            <em class="error invalid-feedback"
                                                v-if="form.errors.has('client')">
                                                {{ form.errors.get('client') }}
                                            </em>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="input-group">Method</label>
                                        <div class="input">
                                            <div class="form-check">
                                                <input type="radio"
                                                       @change="form.errors.clear()"
                                                       class="form-check-input" value=0
                                                       v-model="form.recall_method">
                                                <label class="form-check-label">By Assigned Date</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio"
                                                       @change="form.errors.clear()"
                                                       class="form-check-input"
                                                       value=1
                                                       v-model="form.recall_method">
                                                <label class="form-check-label">By File</label>
                                            </div>
                                            <em class="error invalid-feedback"
                                                v-if="form.errors.has('recall_method')">
                                                {{ form.errors.get('recall_method') }}
                                            </em>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group" v-if="form.recall_method === '1'">
                                        <label class="input-group">File Input Type</label>
                                        <div class="input">
                                            <div class="form-check">
                                                <input type="radio"
                                                       @change="form.errors.clear()"
                                                       class="form-check-input"
                                                       value=0
                                                       v-model="form.file_type">
                                                <label class="form-check-label">Generic</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio"
                                                       @change="form.errors.clear()"
                                                       class="form-check-input"
                                                       value=1
                                                       v-model="form.file_type">
                                                <label class="form-check-label">Client Specific</label>
                                            </div>
                                            <em class="error invalid-feedback"
                                                v-if="form.errors.has('file_type')">
                                                {{ form.errors.get('file_type') }}
                                            </em>
                                        </div>
                                    </div>
                                    <div class="form-group" v-else>
                                        <label>Assign Date</label>
                                        <div class="input-group">
                                            <datepicker style="flex: 1 1 auto;"
                                                        input-class="form-control text-right"
                                                        @change="form.errors.clear()"
                                                        v-model="form.assigned_date">
                                            </datepicker>
                                            <em class="error invalid-feedback"
                                                v-if="form.errors.has('assigned_date')">
                                                {{ form.errors.get('assigned_date') }}
                                            </em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4" v-if="form.recall_method === '1'">
                                    <div class="form-group" v-if="form.file_type === '0'">
                                        <label class="input-group">Generic Type</label>
                                        <div class="input">
                                            <div class="form-check">
                                                <input type="radio"
                                                       @change="form.errors.clear()"
                                                       class="form-check-input"
                                                       value=0
                                                       v-model="form.generic_type">
                                                <label class="form-check-label">By Client Ref No.</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio"
                                                       @change="form.errors.clear()"
                                                       class="form-check-input"
                                                       value=1
                                                       v-model="form.generic_type">
                                                <label class="form-check-label">By Original Accnt Num</label>
                                            </div>
                                            <em class="error invalid-feedback"
                                                v-if="form.errors.has('generic_type')">
                                                {{ form.errors.get('generic_type') }}
                                            </em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4" v-if="form.recall_method === '1'">
                                    <div class="form-group">
                                        <label>File (txt/csv)</label>
                                        <div class="input-group">
                                            <input type="file"
                                                   @change="fileChanged"
                                                   ref="fileUpload">
                                            <em class="error invalid-feedback"
                                                v-if="form.errors.has('file_input')">
                                                {{ form.errors.get('file_input') }}
                                            </em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-md-12">
                                <div class="form-actions pull-right">
                                    <button type="submit"
                                            class="btn btn-primary"
                                            @click.prevent="generateReport"
                                            :disabled="isLoading"
                                            v-html="persistButtonText">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
	import ClientLists from './Store';
	import Datepicker from 'vuejs-datepicker';

	export default {

		components: {
			Datepicker
		},

		store: ClientLists,

		data() {
			return {
				persistButtonText: `<i class="fa fa-save"></i> Generate Report`,
				isLoading: false,

				form: new Form({
					client: '',
					recall_method: '',
					assigned_date: '',
					file_type: '',
					generic_type: '',
					file_input: '',
				}),
			}
		},

		beforeCreate() {
			this.$store.dispatch('loadClientLists');
		},

		methods: {
			fileChanged(e) {
				if (!e.target.files.length) return;

				this.form.errors.clear();

				this.form.file_input = e.target.files[0];
			},

			generateReport() {
				let tempButtonText = this.persistButtonText;

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				let formData = new FormData();

				formData.append('client', this.form.client);
				formData.append('recall_method', this.form.recall_method);
				formData.append('assigned_date', new Date(this.form.assigned_date).toUTCString());
				formData.append('file_type', this.form.file_type);
				formData.append('generic_type', this.form.generic_type);
				formData.append('file_input', this.form.file_input);

				axios.post('/admin/closures/recalls', formData,
					{
						headers: {
							'Content-Type': 'multipart/form-data'
						},
						responseType: 'blob',
					},
				).then((response) => {
					this.isLoading = false;
					this.persistButtonText = tempButtonText;

					const url = window.URL.createObjectURL(new Blob([response.data]));
					const link = document.createElement('a');
					let date = moment(this.form.assigned_date).format("MM-DD-YYYY");
					link.href = url;
					link.setAttribute('download', `${this.form.client} - ${date}.xlsx`);
					document.body.appendChild(link);
					link.click();
				}).catch((error) => {

                    if (error.response.status === 422) {
						const {data} = error.response;

						const reader = new FileReader();

                        reader.onload = (() => {
                            const message = JSON.parse(reader.result);

                            let error = {data: message};

                            this.form.onFail(error);
                        });

                        reader.readAsText(data);
                    } else {
                        lib.swalError(error.message);
                    }

						// const file = FileReader.readAsText(data);
						//
						// console.log(error.response);
                    this.isLoading = false;
                    this.persistButtonText = tempButtonText;

						// const blb = new Blob([errors.data]);
						// const reader = new FileReader();
						//
						// reader.addEventListener('loadend', (e) => {
						// 	const text = e.srcElement.result;
						// 	console.log(text);
						// });
						//
						// reader.readAsText(blb);
					}
				)

				// this.form.patch('./profile')
				// 	.then((data) => {
				// 		this.isLoading = false;
				// 		this.persistButtonText = tempButtonText;
				//
				// 		this.form.first_name = data.first_name;
				// 		this.form.last_name = data.last_name;
				// 		this.form.email = data.email;
				// 		this.form.old_password = '';
				// 		this.form.password = '';
				// 		this.form.password_confirmation = '';
				//
				// 		lib.swalSuccess("Profile successfully updated");
				// 	})
				// 	.catch((error) => {
				// 		this.isLoading = false;
				// 		this.persistButtonText = tempButtonText;
				// 	});
			}
		},

		computed: {
			client_lists() {
				return this.$store.state.client_lists;
			},
		}
	}
</script>