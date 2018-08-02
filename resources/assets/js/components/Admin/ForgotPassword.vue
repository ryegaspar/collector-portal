<template>
    <div class="card-body">
        <form @submit.prevent="">
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
                       v-model="form.email"
                       placeholder="Email">
            </div>
            <div class="row">
                <button type="submit"
                        class="btn btn-primary m-auto"
                        :disabled="isLoading"
                        ref="submit"
                        v-html="submitButton">
                </button>
            </div>
        </form>
    </div>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					email: '',
				},

				isLoading: false,
				submitButton: 'Send Password Reset Link',
				hasErrors: false,
			}
		},

		methods: {
			openForgotPassword() {

			}
		}
	}
</script>