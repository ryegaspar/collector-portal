class Errors {

	/**
	 * create a new Errors instance.
	 */
	constructor() {
		this.errors = {};
	}

	/**
	 * determine if an error exists for the given field.
	 *
	 * @param field
	 * @returns {boolean}
	 */
	has(field) {
		return this.errors.hasOwnProperty(field);
	}

	/**
	 * determine if we have any errors.
	 *
	 * @returns {boolean}
	 */
	any() {
		return Object.keys(this.errors).length > 0;
	}

	/**
	 * retrieve the error message for a field
	 *
	 * @param field
	 * @returns {*}
	 */
	get(field) {
		if (this.errors[field]) {
			return this.errors[field][0];
		}
	}

	/**
	 * record the new errors
	 *
	 * @param errors
	 */
	record(errors) {
		this.errors = errors;
	}

	/**
	 * clear one or all error fields.
	 *
	 * @param field
	 */
	clear(field) {
		if (field) {
			delete this.errors[field];
			return;
		}

		this.errors = {};
	}
}

export default Errors;