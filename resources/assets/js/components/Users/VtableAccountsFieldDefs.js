export default [
	{
		name: 'DBR_NO',
		sortField: 'DBR_NO',
		title: 'Debter No',
		visible: true,
	},
	{
		name: 'full_name',
		sortField: 'DBR_NAME1',
		title: 'Name',
		visible: true,
	},
	{
		name: 'assigned_amount',
		sortField: 'DBR_ASSIGN_AMT',
		title: 'Assigned Amount',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'last_trust_amount',
		sortField: 'DBR_LAST_TRUST_AMT',
		title: 'Last Transaction Amt',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'received_total',
		sortField: 'DBR_RECVD_TOT',
		title: 'Received Total',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'client',
		sortField: 'DBR_CL_MISC_1',
		title: 'Client',
		visible: false,
	},
	{
		name: 'last_worked',
		sortField: 'DBR_LAST_WORKED_O',
		title: 'Last Worked',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'last_transaction',
		sortField: 'DBR_LAST_TRUST_DATE_O',
		title: 'Last Transaction',
		dataClass: 'text-right',
		visible: false,
	}


]