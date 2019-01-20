import * as CorrespondenceLogOptions from '../../utilities/CorrespondenceLogOptions';

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
		name: 'correspondence_from',
		sortField: 'correspondence_from',
		title: 'Origination',
		titleClass: 'text-center',
		visible: true,

	},
	{
		name: 'correspondence_type',
		sortField: 'correspondence_type',
		title: 'Type',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'assigned_department',
		sortField: 'assigned_department',
		title: 'Department',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'creator_name',
		sortField: 'creator_name',
		title: 'creator_name',
		titleClass: 'text-center',
		visible: false,
	},
	{
		name: 'account_no',
		sortField: 'account_no',
		title: 'Unifin ID',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'client_name',
		sortField: 'client_name',
		title: 'Client Name',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'consumer_name',
		sortField: 'consumer_name',
		title: 'Consumer Name',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'correspondence_contact',
		sortField: 'correspondence_contact',
		title: 'Preferred Contact Method',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: false,
	},
	{
		name: 'status',
		sortField: 'status',
		title: 'Status',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let obj = (CorrespondenceLogOptions.status).find(o => +o.id === +value);
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