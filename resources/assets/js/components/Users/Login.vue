<template>
    <div class="card-body">
        <h1>Login</h1>
        <p class="text-muted">Sign In to your account</p>
        <form @submit.prevent="submit">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-user"></i></span>
                </div>
                <input type="text"
                       class="form-control"
                       :class="hasErrors ? 'is-invalid': ''"
                       v-model="form.username"
                       placeholder="Username">
                <em class="error invalid-feedback" v-if="hasErrors">Invalid username or password</em>
            </div>
            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-lock"></i></span>
                </div>
                <input type="password"
                       class="form-control"
                       :class="hasErrors ? 'is-invalid': ''"
                       v-model="form.password"
                       placeholder="Password">
            </div>
            <div class="input-group mb-4">
                <input type="checkbox"
                       class="input-group-prepend"
                       v-model="form.remember">
                <div>
                    <span style="margin-left:10px">Remember me</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <button type="submit"
                            class="btn btn-primary px-4"
                            :disabled="isLoading" v-html="submitButton"></button>
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
			}
        },

		methods: {
			submit() {
				this.isLoading = true;
				this.submitButton = `<span><i class="fa fa-spinner fa-spin"></i></span>`;
				let url = './login';
				axios.post(url, this.form)
                    .catch(() => {
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