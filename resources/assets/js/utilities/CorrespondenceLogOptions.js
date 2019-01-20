//Don't change the values, as these are persisted to the database, instead, just add a value if needed.

export const status = [
	{
		id: 0,
		name: 'New Correspondence',
		badge: 'badge-warning'
	},
	{
		id: 1,
		name: 'Currently In Progress',
		badge: 'badge-info'
	},
	{
		id: 2,
		name: 'Completed',
		badge: 'badge-success'
	}
];

export const correspondence_from = [
	{
		id: 0,
		name: 'Client'
	},
	{
		id: 1,
		name: 'Consumer'
	},
	{
		id: 2,
		name: 'Third-Party'
	},
];

export const correspondence_type = [
	{
		id: 0,
		name: 'E-mail'
	},
	{
		id: 1,
		name: 'Voicemail'
	},
	{
		id: 2,
		name: 'Letter'
	},
];

export const assigned_department = [
	{
		id: 0,
		name: 'Collections/Operations'
	},
	{
		id: 1,
		name: 'Compliance'
	},
	{
		id: 2,
		name: 'Client Relations'
	},
	{
		id: 3,
		name: 'Finance/Payment Processing'
	},
	{
		id: 4,
		name: 'Human Resources'
	},
	{
		id: 5,
		name: 'Sales'
	},
	{
		id: 6,
		name: 'Technology'
	},
];
