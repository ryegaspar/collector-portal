<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create a Script</div>
                    <div class="card-body">
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-1 col-form-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text"
                                           class="form-control"
                                           v-model="form.title">
                                    <em class="error invalid-feedback"
                                        v-if="form.errors.has('title')">
                                        {{ form.errors.get('title') }}
                                    </em>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-1 col-form-label">Publish</label>
                                <div class="checkbox col-md-8">
                                    <input type="checkbox" class="form-group mt-2"
                                           v-model="form.status">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <tinymce id="content"
                                             class="mb-3"
                                             :value="form.content"
                                             v-model="form.content">
                                    </tinymce>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions pull-right">
                                    <button type="submit"
                                            class="btn btn-primary"
                                            @click.prevent="submit"
                                            :disabled="isLoading"
                                            v-html="persistButtonText">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import Tinymce from "./TinymceVue"

	export default {
		components: {
			Tinymce
		},

		data() {
			return {
				persistButtonText: `<i class="fa fa-save"></i> Save`,
				isLoading: false,
				isAdd: true,

				form: new Form({
					title: '',
					content: '',
                    status: false,
				}),
			}
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let url = '/admin/scripts';
				let notifyMessage = "Successfully created a script";

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated user";
					action = 'patch';
					url = `./users/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						swal({
							title: "Success",
							text: notifyMessage,
							icon: 'success',
							timer: 1250
						});
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						if (!this.isAdd) {
							swal({
								title: "Error",
								text: `Unable to create a script`,
								icon: 'warning',
								timer: 1250
							});
						}

					})

			}
		}

	}
</script>