//Don't change the values, as these are persisted to the database, instead, just add a value if needed.

export const request_methods = [
	{
		id: 0,
		name: 'Address',
	},
	{
		id: 1,
		name: 'E-mail'
	},
	{
		id: 2,
		name: 'FAX'
	}
];

export const borrower_types = [
	{
		id: 0,
		name: 'Borrower',
	},
	{
		id: 1,
		name: 'Co-Borrower'
	}
];

export const status = [
	{
		id: 0,
		name: 'Requested',
		badge: 'badge-warning'
	},
	{
		id: 1,
		name: 'Sent',
		badge: 'badge-success'
	},
	{
		id: 2,
		name: 'Rejected',
		badge: 'badge-danger'
	}
];
