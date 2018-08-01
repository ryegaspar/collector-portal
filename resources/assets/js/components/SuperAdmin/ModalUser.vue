<template>
    <div class="modal fade" id="modalUser" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-if="isAdd">Add User</h4>
                    <h4 class="modal-title" v-else>Edit User</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="" @keydown="form.errors.clear()">
                        <fieldset class="form-group">
                            <label>Username:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       :disabled="!isAdd"
                                       v-model="form.username">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('username')">
                                    {{ form.errors.get('username') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>First Name:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.first_name">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('first_name')">
                                    {{ form.errors.get('first_name') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Last Name:</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control text-right"
                                       v-model="form.last_name">
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('last_name')">
                                    {{ form.errors.get('last_name') }}
                                </em>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Group</label>
                            <div class="input-group">
                                <select class="form-control"
                                        v-model="form.access_level"
                                        @change="form.errors.clear()">
                                    <option value="1">Super Admin</option>
                                    <option value="2">Admin</option>
                                </select>
                                <em class="error invalid-feedback"
                                    v-if="form.errors.has('access_level')">
                                    {{ form.errors.get('access_level') }}
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
	export default {
		props: [
			'isAdd',
            'formData'
		],

		components: {
		},

		data() {
			return {
				persistButtonClass: 'btn btn-success',
				persistButtonText: 'Add',
				isLoading: false,

				form: new Form({
					username: '',
					last_name: '',
					first_name: '',
                    access_level: '',
				}),

                updateID: '',
			}
		},

        created() {
			this.$events.$on('modal-reset', eventData => this.onResetModal());
			this.$events.$on('modal-edit', eventData => this.onEditModal(eventData));
        },

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let url = './users';
				let notifyMessage = "Successfully added user";

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

						$("#modalUser").modal('hide');

						swal({
                            title: "Success",
                            text: notifyMessage,
                            icon: 'success',
                            timer: 1250
                        });

						this.$emit('submitted');
						// this.$emit('reload');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						swal({
							title: "Error",
							text: `Unable to update user`,
							icon: 'warning',
							timer: 1250
						});
					})

			},

            onResetModal() {
				this.form.errors.clear();
				this.form.reset();
            },

            onEditModal(e) {
				_.assign(this.form, e);
				this.updateID = e.id;
            }
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