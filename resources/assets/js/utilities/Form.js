import Errors from './Errors';

class Form {

	/**
	 * create a new form instance.
	 *
	 * @param data
	 */
	constructor(data) {
		this.originalData = data;

		for (let field in data) {
			this[field] = data[field];
		}

		this.errors = new Errors();
	}

	/**
	 * fetch all relevant data for the form.
	 */
	data() {
		let data = {};

		for (let property in this.originalData) {
			data[property] = this[property];
		}

		return data;
	}

	/**
	 * reset the form fields.
	 */
	reset() {
		for (let field in this.originalData) {
			this[field] = '';
		}

		this.errors.clear();
	}

	/**
	 * send a POST request to the given URL.
	 *
	 * @param url
	 * @returns {*}
	 */
	post(url) {
		return this.submit('post', url);
	}

	/**
	 * send a PATCH request to the given URL.
	 * @param url
	 */
	patch(url) {
		return this.submit('patch', url);
	}

	/**
	 * send a DELETE request to the given URL.
	 * @param url
	 * @returns {*}
	 */
	delete(url) {
		return this.submit('delete', url);
	}

	submit(requestType, url) {
		return new Promise((resolve, reject) => {
			axios[requestType](url, this.data())
				.then(response => {
					this.onSuccess(response.data);

					resolve(response.data);
				})
				.catch(error => {
					if (error.response.status == 422) {
						this.onFail(error.response);
					}

					reject(error.response);
				});
		});
	}

	/**
	 * handle a successful form submission.
	 *
	 * @param data
	 */
	onSuccess(data) {
		this.reset();
	}

	/**
	 * handle a failed form submission.
	 *
	 * @param errors
	 */
	onFail(errors) {
		this.errors.record(errors.data.errors);
	}
}

export default Form;