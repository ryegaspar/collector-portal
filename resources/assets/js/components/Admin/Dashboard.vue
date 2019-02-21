<template>
    <div class="animated fadeIn">
        <div class="row py-4">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Today's Totals</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <dashboard-bar-chart :graphData="barData" :height="175"
                                                 v-if="hasData"></dashboard-bar-chart>
                            <div v-else>No data</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-show="hasData">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-flex">
                        <h4 class="card-title">Today's Total (Data)</h4>
                        <button class="btn btn-info rounded" @click="redirectTodaysTotals()">Go to Detailed Totals</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped table-bordered">
                            <colgroup>
                                <col>
                                <col span="3" style="background-color: lightcyan">
                                <col span="3" style="background-color: lavender">
                                <col span="3" style="background-color: lightcyan">
                                <col span="3" style="background-color: lavender">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Collection Group</th>
                                    <th>GFT</th>
                                    <th># of pmts</th>
                                    <th>AVG pmt</th>
                                    <th>Current Month</th>
                                    <th># of pmts</th>
                                    <th>Avg pmt</th>
                                    <th>30 Day</th>
                                    <th># of pmts</th>
                                    <th>Avg pmt</th>
                                    <th>All</th>
                                    <th># of pmts</th>
                                    <th>Avg pmt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="r_data in remapped_data">
                                    <td>{{ r_data.group }}</td>
                                    <td class="text-right">{{ r_data.gft_total }}</td>
                                    <td class="text-center">{{ r_data.gft_count }}</td>
                                    <td class="text-right">{{ r_data.gft_avg }}</td>
                                    <td class="text-right bg-lavender">{{ r_data.this_month_total }}</td>
                                    <td class="text-center bg-lavender">{{ r_data.this_month_count }}</td>
                                    <td class="text-right bg-lavender">{{ r_data.this_month_avg }}</td>
                                    <td class="text-right">{{ r_data.thirty_days_total }}</td>
                                    <td class="text-center">{{ r_data.thirty_days_count}}</td>
                                    <td class="text-right">{{ r_data.thirty_days_avg }}</td>
                                    <td class="text-right bg-lavender">{{ r_data.all_total}}</td>
                                    <td class="text-center bg-lavender">{{ r_data.all_count}}</td>
                                    <td class="text-right bg-lavender">{{ r_data.all_avg}}</td>
                                </tr>
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td class="text-right">{{ sum(gft, 'total').toFixed(2) }}</td>
                                    <td class="text-center">{{ sum(gft, 'count') }}</td>
                                    <td class="text-center">{{ sum(gft, 'avg').toFixed(2) }}</td>
                                    <td class="text-right bg-lavender">{{ sum(thisMonth, 'total').toFixed(2) }}</td>
                                    <td class="text-center bg-lavender">{{ sum(thisMonth, 'count') }}</td>
                                    <td class="text-center bg-lavender">{{ sum(thisMonth, 'avg').toFixed(2) }}</td>
                                    <td class="text-right">{{ sum(thirtyDays, 'total').toFixed(2) }}</td>
                                    <td class="text-center">{{ sum(thirtyDays, 'count') }}</td>
                                    <td class="text-center">{{ sum(thirtyDays, 'avg').toFixed(2) }}</td>
                                    <td class="text-right bg-lavender">{{ sum(all, 'total').toFixed(2) }}</td>
                                    <td class="text-center bg-lavender">{{ sum(all, 'count') }}</td>
                                    <td class="text-center bg-lavender">{{ sum(all, 'avg').toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!--data flipped-->
        <!--<div class="row" v-show="hasData">-->
            <!--<div class="col-md-12">-->
                <!--<div class="card">-->
                    <!--<div class="card-header">-->
                        <!--<h4 class="card-title">Today's Total (Data)</h4>-->
                    <!--</div>-->
                    <!--<div class="card-body">-->
                        <!--<table class="table table-responsive-sm table-bordered table-striped">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th>&nbsp;</th>-->
                                <!--<th v-for="group in groups" class="text-right">{{ group }}</th>-->
                                <!--<th class="text-right">Totals</th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<tr>-->
                                <!--<td class><strong>GFT Total Amount</strong></td>-->
                                <!--<td v-for="data_gft in gft" class="text-right">{{ data_gft.total }}</td>-->
                                <!--<td class="text-right">{{ sum(gft, 'total').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>GFT Count</td>-->
                                <!--<td v-for="data_gft in gft" class="text-right">{{ data_gft.count}}</td>-->
                                <!--<td class="text-right">{{ sum(gft, 'count') }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>GFT Avg</td>-->
                                <!--<td v-for="data_gft in gft" class="text-right">{{ data_gft.avg}}</td>-->
                                <!--<td class="text-right">{{ sum(gft, 'avg').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>This Month Total Amount</td>-->
                                <!--<td v-for="data_thisMonth in thisMonth" class="text-right">{{ data_thisMonth.total}}-->
                                <!--</td>-->
                                <!--<td class="text-right">{{ sum(thisMonth, 'total').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>This Month Count</td>-->
                                <!--<td v-for="data_thisMonth in thisMonth" class="text-right">{{ data_thisMonth.count}}-->
                                <!--</td>-->
                                <!--<td class="text-right">{{ sum(thisMonth, 'count') }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>This Month Avg</td>-->
                                <!--<td v-for="data_thisMonth in thisMonth" class="text-right">{{ data_thisMonth.avg}}</td>-->
                                <!--<td class="text-right">{{ sum(thisMonth, 'avg').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>30 Days Total Amount</td>-->
                                <!--<td v-for="data_thirtyDays in thirtyDays" class="text-right">{{-->
                                    <!--data_thirtyDays.total}}-->
                                <!--</td>-->
                                <!--<td class="text-right">{{ sum(thirtyDays, 'total').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>30 Days Count</td>-->
                                <!--<td v-for="data_thirtyDays in thirtyDays" class="text-right">{{-->
                                    <!--data_thirtyDays.count}}-->
                                <!--</td>-->
                                <!--<td class="text-right">{{ sum(thirtyDays, 'count') }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>30 Days Avg</td>-->
                                <!--<td v-for="data_thirtyDays in thirtyDays" class="text-right">{{ data_thirtyDays.avg}}-->
                                <!--</td>-->
                                <!--<td class="text-right">{{ sum(thirtyDays, 'avg').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>All Total Amount</td>-->
                                <!--<td v-for="data_all in all" class="text-right">{{ data_all.total}}</td>-->
                                <!--<td class="text-right">{{ sum(all, 'total').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>All Count</td>-->
                                <!--<td v-for="data_all in all" class="text-right">{{ data_all.count}}</td>-->
                                <!--<td class="text-right">{{ sum(all, 'count') }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td>All Avg</td>-->
                                <!--<td v-for="data_all in all" class="text-right">{{ data_all.avg}}</td>-->
                                <!--<td class="text-right">{{ sum(all, 'avg').toFixed(2) }}</td>-->
                            <!--</tr>-->
                            <!--</tbody>-->
                        <!--</table>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-flex">
                        <h4 class="card-title mb-0">Collection Hours</h4>
                        <button class="btn btn-info rounded" @click="redirectCollectorHours()">Go to Detailed Hours</button>
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
                                    <td class="text-right" v-if="item.name.match(/Unifin.*/)">{{ (item.time*187.50).toFixed(2)
                                        }}
                                    </td>
                                    <td class="text-right" v-else>{{ (item.time*67.50).toFixed(2) }}</td>
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

		mounted() {
			axios.get('/admin/dashboard/get-data')
				.then(({data}) => {
					this.groups = data.groups;
					this.gft = data.gft;
					this.thisMonth = data.thisMonth;
					this.thirtyDays = data.thirtyDays;
					this.all = data.all;

					this.groups.forEach((item, index) => {
						this.remapped_data.push({
							'group': item,

							'gft_total': this.gft[index].total,
							'gft_count': this.gft[index].count,
							'gft_avg': this.gft[index].avg,

							'this_month_total': this.thisMonth[index].total,
							'this_month_count': this.thisMonth[index].count,
							'this_month_avg': this.thisMonth[index].avg,

							'thirty_days_total': this.thirtyDays[index].total,
							'thirty_days_count': this.thirtyDays[index].count,
							'thirty_days_avg': this.thirtyDays[index].avg,

							'all_total': this.all[index].total,
							'all_count': this.all[index].count,
							'all_avg': this.all[index].avg,
						});
					});
				});
		},

		data() {
			return {
				groups: [],
				gft: [],
				thisMonth: [],
				thirtyDays: [],
				all: [],

				remapped_data: [],
			}
		},

		methods: {
			sum(item, index) {
				if (item !== undefined && item.length !== 0)
					return item.map((a, b) => parseFloat(a[index])).reduce((total, amount) => total + amount);

				return 0;
			},

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

            redirectTodaysTotals() {
				window.location.href = '/admin/todays-totals';
            },

            redirectCollectorHours() {
				window.location.href = '/admin/collector-hours';
            }
		},

		computed: {
			hasData() {
				return this.groups !== undefined && this.groups.length !== 0;
			},

			barData() {
				return {
					labels: this.groups,
					datasets: [
						{
							label: 'GFT',
							backgroundColor: this.backgroundColors(),
							data: this.gft.map((a) => parseFloat(a['total'])),
						},
						{
							label: 'Current Month',
							backgroundColor: this.backgroundColors(),
							data: this.thisMonth.map((a) => parseFloat(a['total'])),
						},
						{
							label: '30 day',
							backgroundColor: this.backgroundColors(),
							data: this.thirtyDays.map((a) => parseFloat(a['total'])),
						},
						{
							label: 'All',
							backgroundColor: this.backgroundColors(),
							data: this.all.map((a) => parseFloat(a['total'])),
							hidden: true,
						}
					]
				}
			},
		}
	}
</script>

<style>
    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
