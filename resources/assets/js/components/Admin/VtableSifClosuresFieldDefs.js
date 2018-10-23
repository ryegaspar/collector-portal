export default [
	{
		name: 'DBR_NO',
		sortField: 'DBR_NO',
		title: 'DBR #',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'DBR_CLI_REF_NO',
		sortField: 'DBR_CLI_REF_NO',
		title: 'Client Ref #',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'DBR_NAME1',
		sortField: 'DBR_NAME1',
		title: 'Name',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let str = value.toLowerCase().replace(",", ", ");
			return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
				function(s){
					return s.toUpperCase();
				});
		}
	},
	{
		name: 'DBR_STATUS',
		sortField: 'DBR_STATUS',
		title: 'Status',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	},
	{
		name: 'DBR_CLIENT',
		sortField: 'DBR_CLIENT',
		title: 'Client Code',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: false
	},
	{
		name: 'DBR_CL_MISC_1',
		sortField: 'DBR_CL_MISC_1',
		title: 'Client',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
		callback: function (value) {
			let str = value.toLowerCase();
			return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
				function(s){
					return s.toUpperCase();
				});
		}
	},
	{
		name: 'chk_count',
		sortField: 'chk_count',
		title: 'PDC Count',
		titleClass: 'text-center',
		dataClass: 'text-center',
		visible: true,
	}
]