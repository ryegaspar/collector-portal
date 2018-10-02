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
		name: 'username',
		sortField: 'username',
		title: 'Username',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'email',
		sortField: 'email',
		title: 'Email',
		titleClass: 'text-center',
		visible: false
	},
	{
		name: 'full_name',
		sortField: 'first_name',
		title: 'Name',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'roles',
		title: 'Role',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (role) {
			if (role[0]) {
				switch (role[0].id) {
					case 1:
						return `<span class="badge badge-pill badge-primary"><i class="fa fa-star"></i> super-admin</span>`;
						break;
					case 2:
						return `<span class="badge badge-pill badge-secondary">admin</span>`;
						break;
					default:
						return `<span class="badge badge-pill badge-warning">${role[0].name}</span>`
				}
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