export function swalSuccess(message) {
	return swal({
		title: "Success",
		text: message,
		icon: "success",
		timer: 1250
	});
}

export function swalError(message) {
	return swal({
		title: "Error",
		text: message,
		icon: "error",
		timer: 1750
	});
}