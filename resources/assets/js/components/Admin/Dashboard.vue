<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Today's Totals</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                            <tr>
                                <th>Collection Group</th>
                                <th class="text-right">GFT</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                <th class="text-right">Current Month</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                <th class="text-right">Next Month</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                <th class="text-right">30 Day</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                <th class="text-right">All</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in summary">
                                    <td v-for="(place, index) in item" :class="getClass(index)">{{ toNumber(place, index) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td class="text-right" v-text="toNumber(totalToday)"></td>
                                    <td class="text-right" v-text="parseInt(totalTodayCount, 10)"></td>
                                    <td class="text-right" v-text="'$' + toNumber(totalToday/totalTodayCount)"></td>
                                    <td class="text-right" v-text="toNumber(totalCurrentMonth)"></td>
                                    <td class="text-right" v-text="parseInt(totalCurrentMonthCount, 10)"></td>
                                    <td class="text-right" v-text="'$' + toNumber(totalCurrentMonth/totalCurrentMonthCount)"></td>
                                    <td class="text-right" v-text="toNumber(totalNextMonth)"></td>
                                    <td class="text-right" v-text="parseInt(totalNextMonthCount, 10)"></td>
                                    <td class="text-right" v-text="'$' + toNumber(totalNextMonth/totalNextMonthCount)"></td>
                                    <td class="text-right" v-text="toNumber(totalNext30)"></td>
                                    <td class="text-right" v-text="parseInt(totalNext30Count, 10)"></td>
                                    <td class="text-right" v-text="'$' + toNumber(totalNext30/totalNext30Count)"></td>
                                    <td class="text-right" v-text="toNumber(totalAll)"></td>
                                    <td class="text-right" v-text="parseInt(totalAllCount, 10)"></td>
                                    <td class="text-right" v-text="'$' + toNumber(totalAll/totalAllCount)"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Collection Hours</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                            <tr>
                                <th>Collection Group</th>
                                <th class="text-right">Number of Active Employees</th>
                                <th class="text-right">Total Hours Worked</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in collectionhours">
                                    <td v-for="(place, index) in item" :class="getClass(index)">{{ toNumber(place, index) }}</td>
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
	export default {
		props: [
            'collectionhours',
            'summary'
        ], 

		methods: {
			toNumber(value, index = '') {
				if (index === 'name')
					return value;
                if (Number.isInteger(value) === true)
                    var number = (Number(value)).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                else
                    var number = (Number(value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				return number;
			},
            calcGroupAverage(value, index = '') {
				if (index === 'name')
					return value;
                if (Number.isInteger(value) === true)
                    var number = (Number(value)).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                else
                    var number = (Number(value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				return number;
			},

			getClass(index) {
				return (index !== 'name' ? 'text-right' : '');
			},

			getTotalSummary(column) {
				let total = 0;
				(this.summary).forEach((obj, index) => {
					total +=parseFloat(obj[column]);
				});
				return total;
			},
		},

        computed: {
			totalToday() {
				return this.getTotalSummary('today');
            },

            totalCurrentMonth() {
				return this.getTotalSummary('currentMonth');
            },

            totalNextMonth() {
				return this.getTotalSummary('nextMonth');
            },

            totalNext30() {
				return this.getTotalSummary('next30');
            },

            totalNext120() {
				return this.getTotalSummary('next120');
            },

            totalAll() {
				return this.getTotalSummary('all');
            },
            
			totalTodayCount() {
				return this.getTotalSummary('todayCount');
            },

            totalCurrentMonthCount() {
				return this.getTotalSummary('currentMonthCount');
            },

            totalNextMonthCount() {
				return this.getTotalSummary('nextMonthCount');
            },

            totalNext30Count() {
				return this.getTotalSummary('next30Count');
            },

            totalNext120Count() {
				return this.getTotalSummary('next120Count');
            },

            totalAllCount() {
				return this.getTotalSummary('allCount');
            }
        }
	}
</script>
