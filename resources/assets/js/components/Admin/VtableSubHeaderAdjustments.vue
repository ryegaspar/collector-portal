<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group-append">
                    <label class="col-form-label-sm mr-1">Date Submitted</label>
                    <date-range-picker class="mr-1"
                                       :startDate="propStartDate"
                                       :endDate="propEndDate"
                                       :ranges=ranges>
                    </date-range-picker>
                    <button type="button"
                            class="btn btn-outline-cyan btn-sm dropdown-toggle mr-1"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fa fa-filter"></i> {{ statusText }} <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu">
                        <!-- list item-->
                        <a v-for="item in statusDropdown"
                           class="dropdown-item"
                           @click="filterStatus(item.code, item.text)"
                           href="#">{{ item.text }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
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
			'propStartDate',
			'propEndDate'
		],

		data() {
			return {
				startDate: '',
				endDate: '',
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'This Week': [moment().startOf('week'), moment().endOf('week')],
					'Last Week': [moment().subtract(1, 'weeks').startOf('week'), moment().subtract(1, 'weeks').endOf('week')],
					'This month': [moment().startOf('month'), moment().endOf('month')],
					'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					'This year': [moment().startOf('year'), moment().endOf('year')],
				},

				statusText: 'Status',
				statusDropdown: [
					{ code: "A", text: "All" },
					{ code: "0", text: "For Review" },
					{ code: "1", text: "Approved" },
					{ code: "2", text: "Denied" },
				]
			}
		},

		methods: {
			filterStatus(code, text) {
				this.statusText = text;
				this.$events.fire('status-change', code);
			}
		},

		computed: {}
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