export default [
	{
		name: 'id',
		sortField: 'id',
		title: 'ID',
		titleClass: 'text-center',
		dataClass: 'text-center',
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
		name: 'active',
		sortField: 'active',
		title: 'Status',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			if (+value) {
				return `<span class="badge badge-pill badge-success">Enabled</span>`;
			}
			return `<span class="badge badge-pill badge-danger">Disabled</span>`;
		}
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]