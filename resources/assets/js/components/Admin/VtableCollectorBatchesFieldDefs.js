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
		name: 'name',
		sortField: 'name',
		title: 'Name',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'collectors_count',
		sortField: 'collectors_count',
		title: 'Num of Collectors',
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
		visible: true,
	},
	{
		name: 'created_at',
		sortField: 'created_at',
		title: 'Date Created',
		titleClass: 'text-center',
		dataClass: 'text-right',
		visible: true
	},
	{
		name: 'start_date',
		sortField: 'start_date',
		title: 'Start Date',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]