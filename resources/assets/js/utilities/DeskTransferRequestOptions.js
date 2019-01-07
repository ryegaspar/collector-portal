//Don't change the values, as these are persisted to the database, instead, just add a value if needed.

export const request_reasons = [
	{
		id: 0,
		name: 'Recovered Account'
	},
	{
		id: 1,
		name: 'Transfer From Manager Desk',
	},
	{
		id: 2,
		name: 'Transfer Per Hold Rules'
	},
	{
		id: 3,
		name: 'Automated Transfer Did Not Happen'
	},
	{
		id: 4,
		name: 'Other'
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
		name: 'Approved',
		badge: 'badge-success'
	},
	{
		id: 2,
		name: 'Rejected',
		badge: 'badge-danger'
	}
];
