import * as DeskTransferRequestOptions from '../../utilities/DeskTransferRequestOptions';

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
		name: 'dbr_no',
		sortField: 'dbr_no',
		title: 'Account Number',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'desk_from',
		sortField: 'dbr_from',
		title: 'Current Desk',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'desk',
		sortField: 'desk',
		title: 'Transfer to Desk',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'creator_name',
		sortField: 'creator_name',
		title: 'Submitted By',
		titleClass: 'text-center',
		dataClass: 'text-center',
	},
	{
		name: 'request_reason',
		sortField: 'request_reason',
		title: 'Reason',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let obj = (DeskTransferRequestOptions.request_reasons).find(o => +o.id === +value);
			return obj.name;
		}
	},
	{
		name: 'status',
		sortField: 'status',
		title: 'Status',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let obj = (DeskTransferRequestOptions.status).find(o => +o.id === +value);
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
		name: 'checked_by.full_name',
		sortField: 'fulfilled_by',
		title: 'Fulfilled By',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true
	},
	{
		name: 'updated_at',
		sortField: 'updated_at',
		title: 'Date Updated',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]