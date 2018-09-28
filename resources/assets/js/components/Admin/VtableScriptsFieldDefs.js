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
		name: 'title',
		sortField: 'title',
		title: 'Title',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'admin.full_name',
		title: 'Author',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'updated_at',
		sortField: 'updated_at',
		title: 'Date Created/Updated',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: 'published_at',
		sortField: 'published_at',
		title: 'Date Published',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true,
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]