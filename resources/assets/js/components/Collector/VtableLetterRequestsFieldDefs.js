import * as LetterRequestOptions from '../../utilities/LetterRequestOptions';

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
		title: 'DBR No.',
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
		name: 'request_method',
		sortField: 'request_method',
		title: 'Method',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let obj = (LetterRequestOptions.request_methods).find(o => +o.id === +value);
			return obj.name;
		}
	},
	{
		name: 'types.name',
		sortField: 'type',
		title: 'Type',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'borrower_type',
		sortField: 'borrower_type',
		title: 'Borrower/Co-Borrower',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: false,
		callback: function (value) {
			let obj = (LetterRequestOptions.borrower_types).find(o => +o.id === +value);
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
			let obj = (LetterRequestOptions.status).find(o => +o.id === +value);
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