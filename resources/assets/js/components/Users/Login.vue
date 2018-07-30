<template>
    <div class="card-body">
        <h1 style="color: #94a0b2">Login</h1>
        <p class="text-muted">Sign In to your account</p>
        <form @submit.prevent="submit">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="background-color: transparent;border: none">
                        <i class="icon-user"></i>
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
                    <span class="input-group-text" style="background-color: transparent;border: none">
                        <i class="icon-lock"></i>
                    </span>
                </div>
                <input type="password"
                       class="form-control"
                       :class="hasErrors ? 'is-invalid': ''"
                       style="border: 1px solid #888"
                       v-model="form.password"
                       @focus="$event.target.select()"
                       ref="password"
                       placeholder="Password">
            </div>
            <div class="input-group mb-4">
                <em class="error invalid-feedback" v-if="hasErrors">{{ errorMessage }}</em>
            </div>
            <div class="row">
                <div class="col-6"></div>
                <div class="col-6">
                    <button type="submit"
                            class="btn btn-primary px-4 pull-right"
                            :disabled="isLoading"
                            v-html="submitButton">
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
                errorMessage: ''
			}
        },

		methods: {
			submit() {
				this.isLoading = true;
				this.submitButton = `<span><i class="fa fa-spinner fa-spin"></i></span>`;
				let url = './login';
				axios.post(url, this.form)
                    .catch(() => {
						this.$refs.password.focus();
                        this.errorMessage = 'invalid username/password';
						this.hasErrors = true;
						this.isLoading = false;
						this.submitButton = 'Login';
                    })
                    .then(({data: {redirect}}) => {
                    	this.submitButton = 'Login';
                    	this.isLoading = false;
                    	location.assign(redirect);
                    });
            }
        }
    }
</script>