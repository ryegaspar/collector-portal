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
		name: 'user.full_name',
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
		name: 'status',
		sortField: 'status',
		title: 'Status',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			if (value) {
				return `<span class="badge badge-pill badge-success">Published</span>`;
			}
			return `<span class="badge badge-pill badge-info">Unpublished</span>`;
		}
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]