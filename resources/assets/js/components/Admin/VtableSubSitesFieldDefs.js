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
		name: 'site.name',
		sortField: 'site_id',
		title: 'Site',
		titleClass: 'text-center',
		visible: true,
	},
	{
		name: 'has_team_leaders',
		sortField: 'has_team_leaders',
		title: 'Team Leaders',
		titleClass: 'text-center',
		visible: false,
	},
	{
		name: '__slot:actions',
		title: 'Actions',
		titleClass: 'text-center',
		visible: true
	}
]