<template>
    <div class="animated fadeIn">
        <form @submit.prevent="save" @keydown="form.errors.clear()">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Profile</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.first_name">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('first_name')">
                                            {{ form.errors.get('first_name') }}
                                        </em>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.last_name">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('last_name')">
                                            {{ form.errors.get('last_namme') }}
                                        </em>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" v-model="form.email">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('email')">
                                            {{ form.errors.get('email') }}
                                        </em>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Password</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Old Password</label>
                                        <input type="password"
                                               class="form-control"
                                               v-model="form.old_password">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('old_password')">
                                            {{ form.errors.get('old_password') }}
                                        </em>
                                    </div>
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password"
                                               class="form-control"
                                               v-model="form.password">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('password')">
                                            {{ form.errors.get('password') }}
                                        </em>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password"
                                               class="form-control"
                                               v-model="form.password_confirmation">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('password_confirmation')">
                                            {{ form.errors.get('password_confirmation') }}
                                        </em>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-actions pull-right">
                                <button type="submit"
                                        class="btn btn-primary"
                                        @click.prevent="save"
                                        :disabled="isLoading"
                                        v-html="persistButtonText">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
	export default {
		data() {
			return {
				persistButtonText: `<i class="fa fa-save"></i> Save`,
				isLoading: false,

				form: new Form({
					first_name: '',
					last_name: '',
                    email: '',
					old_password: '',
					password: '',
					password_confirmation: '',
				}),
			}
		},

		created() {
			axios.get('/admin/profile')
                .then(({data}) => {
                	this.form.first_name = data.first_name;
                	this.form.last_name = data.last_name;
                	this.form.email = data.email;
                });
		},

        methods: {
			save() {
				let tempButtonText = this.persistButtonText;

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				this.form.patch('./profile')
                    .then((data) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						this.form.first_name = data.first_name;
						this.form.last_name = data.last_name;
						this.form.email = data.email;
						this.form.old_password = '';
						this.form.password = '';
						this.form.password_confirmation = '';

						swal({
							title: "Success",
							text: "Profile successfully updated",
							icon: 'success',
							timer: 1250
						});
                    })
                    .catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;
                    });
            }
        }
	}
</script>