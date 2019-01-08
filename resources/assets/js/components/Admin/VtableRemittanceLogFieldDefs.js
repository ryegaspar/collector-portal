import * as RemittanceLogOptions from '../../utilities/RemittanceLogOptions';

export default [
	{
		name: 'id',
		sortField: 'id',
		title: 'ID',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: false,
	},
	{
		name: 'client_name',
		sortField: 'client_name',
		title: 'Client Name',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'sub_client_name',
		sortField: 'sub_client_name',
		title: 'Client Group',
		titleClass: 'text-center',
		visible: false,
	},
	{
		name: 'remit_date',
		sortField: 'remit_date',
		title: 'Remit Date',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'period_start_date',
		sortField: 'period_start_date',
		title: 'Period Start',
		titleClass: 'text-center',
		visible: false,
	},
	{
		name: 'period_end_date',
		sortField: 'period_end_date',
		title: 'Period End',
		titleClass: 'text-center',
		visible: false,
	},
	{
		name: 'total_collections',
		sortField: 'total_collections',
		title: 'Total Collections',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'total_client_collections',
		sortField: 'total_client_collections',
		title: 'Total Direct Payments',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: false,
	},
	{
		name: 'commission_amount',
		sortField: 'commission_amount',
		title: 'Commission Amount',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'remit_amount',
		sortField: 'remit_amount',
		title: 'Remittance Amount',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'creator_name',
		sortField: 'creator_name',
		title: 'Submitted By',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: false,
	},
	{
		name: 'report_sent',
		sortField: 'report_sent',
		title: 'Report Sent',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let obj = (RemittanceLogOptions.report_sent).find(o => +o.id === +value);
			return `<span class="badge badge-pill ${obj.badge}">${obj.name}</span>`;
		}
	},
	{
		name: 'remittance_sent',
		sortField: 'remittance_sent',
		title: 'Remittance Sent',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let obj = (RemittanceLogOptions.remittance_sent).find(o => +o.id === +value);
			return `<span class="badge badge-pill ${obj.badge}">${obj.name}</span>`;
		}
	},
	{
		name: 'commission_recieved',
		sortField: 'commission_recieved',
		title: 'Commission Received',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let obj = (RemittanceLogOptions.commission_recieved).find(o => +o.id === +value);
			return `<span class="badge badge-pill ${obj.badge}">${obj.name}</span>`;
		}
	},
	{
		name: 'created_at',
		sortField: 'created_at',
		title: 'Date Created',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: false,
	},
	{
		name: 'updated_at',
		sortField: 'updated_at',
		title: 'Date Updated',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: false,
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]