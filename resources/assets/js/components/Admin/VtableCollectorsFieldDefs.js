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
		name: 'full_name',
		sortField: 'first_name',
		title: 'Name',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'desk',
		sortField: 'desk',
		title: 'Desk',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'tiger_user_id',
		sortField: 'tiger_user_id',
		title: 'CollectOne',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'sub_site.name',
		sortField: 'sub_site_id',
		title: 'Sub Site',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true
	},
	{
		name: 'commission_structure_id',
		sortField: 'commission_structure_id',
		title: 'Commission Structure',
		titleClass: 'text-center',
		visible: false,
	},
	{
		name: 'start_date',
		sortField: 'start_date',
		title: 'Start Date',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'start_full_month_date',
		sortField: 'start_full_month_date',
		title: 'Start Full Month',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'active',
		sortField: 'active',
		title: 'Status',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			if (value) {
				return `<span class="badge badge-pill badge-info">Active</span>`;
			} else {
				return `<span class="badge badge-pill badge-danger">Inactive</span>`;
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