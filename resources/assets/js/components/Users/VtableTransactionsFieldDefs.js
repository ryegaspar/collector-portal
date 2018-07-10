export default [
	{
		name: 'PAY_DBR_NO',
		sortField: 'PAY_DBR_NO',
		title: 'Debter No',
		visible: true,
	},
	{
		name: 'full_name',
		sortField: 'PAY_NAME',
		title: 'Name',
		visible: true
	},
	{
		name: 'PAY_AMT',
		sortField: 'PAY_AMT',
		title: 'Amount',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'PAY_COMM',
		sortField: 'PAY_COMM',
		title: 'Agency Comm.',
		dataClass: 'text-right',
		visible: true
	},
	{
		name: 'payment_type',
		sortFiled: 'PAY_TYPE',
		title: 'Type',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'PAY_STATUS',
		sortField: 'PAY_STATUS',
		title: 'Status',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			switch (value) {
				case 'T':
					return `<span class="badge badge-pill badge-success">Payment Posted</span>`;
				case 'R':
					return `<span class="badge badge-pill badge-info">In Process</span>`;
				case 'H':
					return `<span class="badge badge-pill badge-warning">Hold for Process</span>`;
				case 'P':
					return `<span class="badge badge-pill badge-primary">Pending Posting</span>`;
			}
		}
	},
	{
		name: 'pay_date',
		sortField: 'PAY_DATE_O',
		title: 'Date',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'Client_Name',
		sortField: 'Client_Name',
		title: 'Client Name',
		visible: false
	}
]