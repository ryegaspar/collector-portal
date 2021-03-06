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
		name: 'published_at',
		sortField: 'published_at',
		title: 'Date Published',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: false,
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]