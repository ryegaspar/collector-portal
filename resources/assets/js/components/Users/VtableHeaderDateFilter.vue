<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group-append">
                    <button type="button"
                            class="btn btn-outline-info btn-sm dropdown-toggle mr-1"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fa fa-calendar"></i> Payment Date Range <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu">
                        <!-- list item-->
                        <a class="dropdown-item" href="#">Current Pay Period</a>
                        <a class="dropdown-item" href="#">Next Pay Period</a>
                        <a class="dropdown-item" href="#">Previous Pay Period</a>
                        <a class="dropdown-item" href="#">Today</a>
                        <a class="dropdown-item" href="#">Tomorrow</a>
                        <a class="dropdown-item" href="#">Yesterday</a>
                        <a class="dropdown-item" href="#">This Week</a>
                        <a class="dropdown-item" href="#">Last Week</a>
                        <a class="dropdown-item" href="#">This Month</a>
                        <a class="dropdown-item" href="#">Last Month</a>
                        <a class="dropdown-item" href="#">User-Defined Date Range</a>
                    </div>
                    <date-range-picker :start-date="startDate" :end-date="endDate" @input="console.log(value)" :ranges=ranges></date-range-picker>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DateRangePicker from '../DatePicker/DateRangePicker';

	export default {
		components: {
			DateRangePicker
        },

		props: [
		],

		data() {
			return {
				startDate: '2018-06-29',
                endDate: '2018-07-29',
                ranges: {
					'Curent Pay Period': this.currentPayPeriod(),
                    'Next Pay Period': this.nextPayPeriod(),
                    'Previous Pay Period': [],
					'Today': [moment(), moment()],
					'Tomorrow': [],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'This Week': [],
                    'Last Week': [],
					'This month': [moment().startOf('month'), moment().endOf('month')],
					'This year': [moment().startOf('year'), moment().endOf('year')],
					'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }
		},

		methods: {
			currentPayPeriod() {
				if (moment().date() >= 5 && moment().date() <= 20) { // 5-20
					return [moment().set('date', 5), moment().set('date', 20)];
				} else { // 21 - 4
					if (moment().date() >= 20) {
						return [moment().set('date', 21), moment().add(1, 'month').set('date', 4)];
					}
                    return [moment().subtract(1, 'month').set('date', 21), moment().set('date', 4)];
                }
			},

            nextPayPeriod() {
				if (moment().date() >= 5 && moment().date() <= 20) { // 5-20
					return [moment().set('date', 21), moment().add(1, 'month').set('date', 4)];
				} else { // 21 - 4
					if (moment().date() >= 20) {
						return [moment().add(1, 'month').set('date', 5), moment().add(1, 'month').set('date', 20)];
                    }
					return [moment().set('date', 5), moment().set('date', 20)];
				}
            }
		},

		computed: {

		}
	}
</script>

<style>
    .filter-type {
        padding-top: 7px;
        font-size: 15px;
        font-weight: bold;
    }

    .filter-clear {
        z-index: 10;
        position: absolute;
        right: 40px;
        top: 0;
        bottom: 0;
        height: 14px;
        margin: auto;
        font-size: 14px;
        cursor: pointer;
    }

    .filter-bar {
        margin-top: 3px;
        margin-bottom: 5px;
        background: #fcfcfc;
        padding: 5px;
        border: 1px solid #e7e6e8;
    }
</style>