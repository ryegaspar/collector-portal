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
		name: 'full_name',
		sortField: 'first_name',
		title: 'Name',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'access_level',
		sortField: 'access_level',
		title: 'Group',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			switch (value) {
				case '1':
					return `<span class="badge badge-pill badge-primary"><i class="fa fa-star"></i> Super Admin</span>`;
					break;
				case '2':
					return `<span class="badge badge-pill badge-secondary">Admin</span>`;
					break;
			}
		}
	},
	{
		name: 'created_at',
		sortField: 'created_at',
		title: 'Created',
		titleClass: 'text-center',
		dataClass: 'text-right',
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