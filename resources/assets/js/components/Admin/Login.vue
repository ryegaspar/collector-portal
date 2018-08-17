<template>
    <div class="card-body">
        <h1 style="color: #94a0b2">Admin Login</h1>
        <p class="text-muted">Sign In to your account</p>
        <form @submit.prevent="submit">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"
                          style="background-color: transparent;border: none"><i class="icon-user"></i>
                    </span>
                </div>
                <input type="text"
                       class="form-control"
                       style="border: 1px solid #888"
                       :class="hasErrors ? 'is-invalid': ''"
                       v-model="form.username"
                       placeholder="Username">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"
                          style="background-color: transparent;border: none"><i class="icon-lock"></i></span>
                </div>
                <input type="password"
                       class="form-control"
                       style="border: 1px solid #888"
                       :class="hasErrors ? 'is-invalid': ''"
                       v-model="form.password"
                       @focus="$event.target.select()"
                       ref="password"
                       placeholder="Password">
            </div>
            <div class="input-group mb-4 ml-5">
                <input type="checkbox"
                       class="input-group-prepend"
                       v-model="form.remember">
                <div>
                    <span style="margin-left:10px;color: #94a0b2">Remember me</span>
                </div>
                <em class="error invalid-feedback" v-if="hasErrors">{{ errorMessage }}</em>
            </div>
            <div class="row">
                <div class="col-6">
                    <button type="submit"
                            class="btn btn-primary px-4"
                            :disabled="isLoading"
                            ref="submit"
                            v-html="submitButton">
                    </button>
                </div>
                <div class="col-6">
                    <button type="button"
                            class=" btn btn-link px-0"
                            @click="openForgotPassword">Forgot password?
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					username: '',
					password: '',
					remember: false,
				},
				submitButton: 'Login',
				isLoading: false,
				hasErrors: false,
                errorMessage: '',
			}
		},

		methods: {
			submit() {
				this.$refs.submit.focus();
				this.isLoading = true;
				this.submitButton = `<span><i class="fa fa-circle-o-notch fa-spin"></i></span>`;
				let url = './login';
				axios.post(url, this.form)
					.catch((error) => {
						this.$refs.password.focus();
						if (error.response.data.errors.username)
							this.errorMessage = error.response.data.errors.username[0];
						else
							this.errorMessage = error.response.data.errors.password[0];
						this.hasErrors = true;
						this.isLoading = false;
						this.submitButton = 'Login';
					})
					.then(({data: {redirect}}) => {
						this.submitButton = 'Login';
						this.isLoading = false;
						location.href = redirect;
					});
			},

            openForgotPassword() {
				window.location.href = '/admin/forgot-password';
            }
		}
	}
</script>