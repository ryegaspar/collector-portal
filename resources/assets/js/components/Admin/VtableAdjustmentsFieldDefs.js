export default [
	{
		name: 'dbr_no',
		sortField: 'dbr_no',
		title: 'Debtor Number',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'collector_name',
		sortField: 'collector_name',
		title: 'Collector',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'desk',
		sortField: 'desk',
		title: 'Desk (Requesting)',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'desk_from',
		sortField: 'desk_from',
		title: 'Desk (Received)',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'formatted_amount',
		sortField: 'amount',
		title: 'Payment Amount',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'formatted_date',
		sortField: 'date',
		title: 'Payment Date',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: false,
	},
	{
		name: 'reviewer.username',
		title: 'Reviewed By',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'created_at',
		sortField: 'created_at',
		title: 'Date Submitted',
		titleClass: 'text-center',
		dataClass: 'text-right',
	},
	{
		name: 'status',
		sortField: 'status',
		title: 'Status',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			switch (value) {
				case '0':
					return `<span class="badge badge-pill badge-info">To be reviewed</span>`;
				case '1':
					return `<span class="badge badge-pill badge-success">Approved</span>`;
				case '2':
					return `<span class="badge badge-pill badge-danger">Denied</span>`;
			}
		}
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]