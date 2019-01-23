<template>
    <div class="animated fadeIn">
        <div class="row py-4">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Today's Totals
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <dashboard-bar-chart :graphData="gft" :height="175"></dashboard-bar-chart>
                            <!--<gft-chart :graphData="gft"></gft-chart>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        30 Days
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">

                            <!--<gft-chart :graphData="gft"></gft-chart>-->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        All
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <!--<gft-chart :graphData="gft"></gft-chart>-->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        etc
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <!--<gft-chart :graphData="gft"></gft-chart>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Collection Hours</h4>
                        <object align="right"><a href="/admin/collector-hours" class="btn btn-info btn-md">Go to
                            Detailed Hours</a></object>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                            <tr>
                                <th>Collection Group</th>
                                <th class="text-right">Number of Active Employees</th>
                                <th class="text-right">Total Hours Worked</th>
                                <th class="text-right">Current 30 Day</th>
                                <th class="text-right">Current Goal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in collectionhours">
                                <td v-for="(place, index) in item" :class="getClass(index)">{{ toNumber(place, index)
                                    }}
                                </td>
                                <td class="text-right" v-if="item.name.match(/Unifin.*/)">{{ (item.time*125).toFixed(2)
                                    }}
                                </td>
                                <td class="text-right" v-else>{{ (item.time*45).toFixed(2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import DashboardBarChart from './DashboardComponents/DashboardBarChart';

	export default {
		components: {
			DashboardBarChart
		},

		props: [
			'collectionhours',
		],

		methods: {
			toNumber(value, index = '') {
				return value;
			},

			getClass(index) {
				return (index !== 'name' ? 'text-right' : '');
			},

			backgroundColors() {
				return [
					'#ff6384',
					'#4bc0c0',
					'#36a2eb',
					'#cc65fe',
					'#ffce56',
					'#27d214',
					'#d24714',
					'#7314d2',
					'#0c0b10',
				];
			},
		},

		computed: {
			// gft() {
			// 	return {
			// 		labels: ['Emapta - Team R.R', 'Sargas - Team Will', 'Team Leaders - Emapta', 'Team Leaders - Sargas', 'Unifin - Team Juan'],
			// 		datasets: [
			// 			{
			// 				backgroundColor: this.backgroundColors(),
			// 				data: [391.0, 2280.76, 257.35, 100.0, 7984.43],
			// 			},
			// 			{
			// 				backgroundColor: this.backgroundColors(),
			// 				data: [5, 10, 15, 10, 3],
			// 			},
			//             {
			// 				backgroundColor: this.backgroundColors(),
			// 				data: [200, 700, 550, 240, 330],
			//             }
			// 		]
			// 	};
			// }
			gft() {
				return {
					labels: [
						'Emapta - Team R.R',
                        'Sargas - Team Will',
                        'Sargas - Training',
                        'Team Leaders - Emapta',
                        'Unifin - Team Juan'
                    ],
					datasets: [
						{
							label: 'GFT',
							backgroundColor: this.backgroundColors(),
                            // data: [0, 0, 0, 0, 0]
							data: [905, 1570.5, 360, 0, 8974.26]
						},
						{
							label: 'Current Month',
							backgroundColor: this.backgroundColors(),
							data: [2306.12, 4772.77, 360, 86.7, 10961.87]
							// data: [0, 0, 0, 0, 0]
						},
                        {
                        	label: '30 day',
                            backgroundColor: this.backgroundColors(),
                            data: [6960.82, 9987.45, 460, 278.67, 12740.81]
							// data: [0, 0, 0, 0, 0]
						},
                        {
                        	label: 'All',
                            backgroundColor: this.backgroundColors(),
                            data: [17651.16, 24504.07, 460, 278.67, 29803.34],
                            hidden: true,
                        }
					]
				}
			},
		}
	}
</script>
