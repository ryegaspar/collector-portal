export default [
	{
		name: 'dbr_no',
		sortField: 'dbr_no',
		title: 'Debter No',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'name',
		sortField: 'name',
		title: 'Name',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'formatted_date',
		sortField: 'date',
		title: 'Payment Date',
		titleClass: 'text-center',
		dataClass: 'text-right',
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
		name: 'formatted_commission',
		sortField: 'commission',
		title: 'Fee',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
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