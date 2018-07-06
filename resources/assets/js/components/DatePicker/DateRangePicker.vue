<template>
    <div style="position: relative; display: inline-block;">
        <div class="reportrange-text" @click="togglePicker">
            <slot name="input">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                <span>{{startText}} - {{endText}}</span>
                <b class="caret"></b>
            </slot>
        </div>
        <transition name="slide-fade" mode="out-in">
            <div class="daterangepicker dropdown-menu ltr" :class="pickerStyles()" v-if="open"
                 v-on-clickaway="clickAway">

                <div class="calendar left">
                    <div class="daterangepicker_input hidden-xs">
                        <input class="input-mini form-control" type="text" name="daterangepicker_start"
                               :value="startText"/>
                        <i class="fa fa-calendar glyphicon glyphicon-calendar"></i>
                    </div>
                    <div class="calendar-table">
                        <calendar :monthDate="monthDate"
                                  :locale="locale"
                                  :start="start" :end="end"
                                  @nextMonth="nextMonth" @prevMonth="prevMonth"
                                  @dateClick="dateClick" @hoverDate="hoverDate"
                        ></calendar>
                    </div>
                </div>

                <div class="calendar right hidden-xs">
                    <div class="daterangepicker_input">
                        <input class="input-mini form-control" type="text" name="daterangepicker_end"
                               :value="endText"/>
                        <i class="fa fa-calendar glyphicon glyphicon-calendar"></i>
                    </div>
                    <div class="calendar-table">
                        <calendar :monthDate="nextMonthDate"
                                  :locale="locale"
                                  :start="start" :end="end"
                                  @nextMonth="nextMonth" @prevMonth="prevMonth"
                                  @dateClick="dateClick" @hoverDate="hoverDate"
                        ></calendar>
                    </div>
                </div>

                <calendar-ranges :canSelect="in_selection" @clickCancel="open=false" @clickRange="clickRange"
                                 @clickApply="clickedApply" :ranges="ranges" class=" hidden-xs">
                </calendar-ranges>
            </div>
        </transition>
    </div>
</template>

<script>
	import Calendar from '../DatePicker/Calendar'
	import CalendarRanges from '../DatePicker/CalendarRanges'
	import {nextMonth, prevMonth} from '../DatePicker/util'
	import {mixin as clickaway} from 'vue-clickaway'

	export default {
		components: {Calendar, CalendarRanges},
		mixins: [clickaway],
		props: {
			localeData: {
				type: Object,
				default() {
					return {}
				},
			},
			startDate: {
				default() {
					return new Date()
				}
			},
			endDate: {
				default() {
					return new Date()
				}
			},
			ranges: {
				type: Object,
				default() {
					return {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'This month': [moment().startOf('month'), moment().endOf('month')],
						'This year': [moment().startOf('year'), moment().endOf('year')],
						'Last week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
						'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				},
			},
			opens: {
				type: String,
				default: 'center'
			}
		},
		data() {
			let default_locale = {
				direction: 'ltr',
				format: moment.localeData().longDateFormat('L'),
				separator: ' - ',
				applyLabel: 'Apply',
				cancelLabel: 'Cancel',
				weekLabel: 'W',
				customRangeLabel: 'Custom Range',
				daysOfWeek: moment.weekdaysMin(),
				monthNames: moment.monthsShort(),
				firstDay: moment.localeData().firstDayOfWeek()
			}

			// let data = { locale: _locale }
			let data = {locale: {...default_locale, ...this.localeData}}

			data.monthDate = new Date(this.startDate)
			data.start = new Date(this.startDate)
			data.end = new Date(this.endDate)
			data.in_selection = false
			data.open = false

			// update day names order to firstDay
			if (data.locale.firstDay !== 0) {
				let iterator = data.locale.firstDay
				while (iterator > 0) {
					data.locale.daysOfWeek.push(data.locale.daysOfWeek.shift())
					iterator--
				}
			}
			return data
		},
		methods: {
			nextMonth() {
				this.monthDate = nextMonth(this.monthDate)
			},
			prevMonth() {
				this.monthDate = prevMonth(this.monthDate)
			},
			dateClick(value) {
				if (this.in_selection) {
					this.in_selection = false
					this.end = new Date(value)
				} else {
					this.in_selection = true
					this.start = new Date(value)
					this.end = new Date(value)
				}
			},
			hoverDate(value) {
				let dt = new Date(value)
				if (this.in_selection && dt > this.start)
					this.end = dt
			},
			togglePicker() {
				this.open = !this.open
			},
			pickerStyles() {
				return {
					'show-calendar': this.open,
					opensright: this.opens === 'right',
					opensleft: this.opens === 'left',
					openscenter: this.opens === 'center'
				}
			},
			clickedApply() {
				this.open = false
				// this.$emit('update', {startDate: this.start, endDate: this.end})
				this.$events.fire('paydate-change', moment(this.start).format("YYYY-MM-DD"), moment(this.end).format("YYYY-MM-DD"));
			},
			clickAway() {
				if (this.open) {
					this.open = false
				}
			},
			clickRange(value) {
				this.start = new Date(value[0])
				this.end = new Date(value[1])
				this.monthDate = new Date(value[0])
				this.clickedApply()
			},
		},
		computed: {
			nextMonthDate() {
				return nextMonth(this.monthDate)
			},
			startText() {
				return this.start.toLocaleDateString()
			},
			endText() {
				return new Date(this.end).toLocaleDateString()
			}
		},
		watch: {
			startDate(value) {
				this.start = new Date(value)
			},
			endDate(value) {
				this.end = new Date(value)
			}
		}
	}

</script>

<style>
    .range_inputs {
        margin-bottom: 5px;
    }

    .reportrange-text {
        background: #fff;
        cursor: pointer;
        padding: 5px 10px;
        border: 1px solid #ccc;
        width: 100%;
    }

    .daterangepicker.show-calendar {
        display: inline-flex;
    }

    .daterangepicker .ranges {
        width: 160px;
    }

    .ranges {
        width: 130px;
    }

    .show-calendar {
        display: block;
        width: auto;
    }

    div.daterangepicker.opensleft {
        top: 35px;
        right: 10px;
        left: auto;
    }

    div.daterangepicker.openscenter {
        top: 35px;
        right: auto;
        left: 50%;
        transform: translate(-50%, 0);
    }

    div.daterangepicker.opensright {
        top: 35px;
        left: 10px;
        right: auto;
    }

    /* Enter and leave animations can use different */
    /* durations and timing functions.              */
    .slide-fade-enter-active {
        transition: all .2s ease;
    }

    .slide-fade-leave-active {
        transition: all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active for <2.1.8 */
    {
        transform: translateX(10px);
        opacity: 0;
    }

    .daterangepicker {
        position: absolute;
        background: #fff;
        top: 100px;
        left: 20px;
        padding: 4px;
        margin-top: 1px;
        width: 278px
    }

    .daterangepicker .opensleft:before {
        position: absolute;
        top: -7px;
        right: 9px;
        display: inline-block;
        border-right: 7px solid transparent;
        border-bottom: 7px solid #e3e8ec;
        border-left: 7px solid transparent;
        border-bottom-color: #e3e8ec;
        content: ''
    }

    .daterangepicker .opensleft:after {
        position: absolute;
        top: -6px;
        right: 10px;
        display: inline-block;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #fff;
        border-left: 6px solid transparent;
        content: ''
    }

    .daterangepicker .openscenter:after, .daterangepicker .openscenter:before {
        left: 0;
        right: 0;
        width: 0;
        margin-left: auto;
        margin-right: auto;
        display: inline-block;
        content: '';
        position: absolute
    }

    .daterangepicker .openscenter:before {
        top: -7px;
        border-right: 7px solid transparent;
        border-bottom: 7px solid #e3e8ec;
        border-left: 7px solid transparent;
        border-bottom-color: #e3e8ec
    }

    .daterangepicker .openscenter:after {
        top: -6px;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #fff;
        border-left: 6px solid transparent
    }

    .daterangepicker .opensright:before {
        position: absolute;
        top: -7px;
        left: 9px;
        display: inline-block;
        border-right: 7px solid transparent;
        border-bottom: 7px solid #e3e8ec;
        border-left: 7px solid transparent;
        border-bottom-color: #e3e8ec;
        content: ''
    }

    .daterangepicker.opensright:after {
        position: absolute;
        top: -6px;
        left: 10px;
        display: inline-block;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #fff;
        border-left: 6px solid transparent;
        content: ''
    }

    .daterangepicker.dropup {
        margin-top: -5px
    }

    .daterangepicker.dropup:before {
        top: initial;
        bottom: -7px;
        border-bottom: initial;
        border-top: 7px solid #e3e8ec
    }

    .daterangepicker.dropup:after {
        top: initial;
        bottom: -6px;
        border-bottom: initial;
        border-top: 6px solid #fff
    }

    .daterangepicker.dropdown-menu {
        max-width: none;
        z-index: 3000
    }

    .daterangepicker.single .calendar, .daterangepicker.single .ranges {
        float: none
    }

    .daterangepicker .calendar {
        display: none;
        max-width: 270px;
        margin: 4px
    }

    .daterangepicker.show-calendar .calendar {
        display: block
    }

    .daterangepicker .calendar.single .calendar-table {
        border: none
    }

    .daterangepicker .calendar td, .daterangepicker .calendar th {
        white-space: nowrap;
        text-align: center;
        min-width: 32px;
        line-height: 30px
    }

    .daterangepicker .calendar-table {
        border: 1px solid #ddd;
        padding: 4px;
        background: #fff
    }

    .daterangepicker table {
        width: 100%;
        margin: 0
    }

    .daterangepicker table thead {
        background: #f1f3f8
    }

    .daterangepicker td, .daterangepicker th {
        text-align: center;
        width: 20px;
        height: 20px;
        white-space: nowrap;
        cursor: pointer
    }

    .daterangepicker td.off, .daterangepicker td.off.end-date, .daterangepicker td.off.in-range, .daterangepicker td.off.start-date {
        color: #e3e8ec;
        background: #fff
    }

    .daterangepicker option.disabled, .daterangepicker td.disabled {
        color: #e3e8ec;
        cursor: not-allowed;
        text-decoration: line-through
    }

    .daterangepicker td.available:hover, .daterangepicker th.available:hover {
        background: #f1f3f8
    }

    .daterangepicker td.in-range {
        background: #ccecf8;
        border-radius: 0
    }

    .daterangepicker td.active, .daterangepicker td.active:hover {
        background-color: #20a8d8;
        border-color: #20a8d8;
        color: #fff
    }

    .daterangepicker td.week, .daterangepicker th.week {
        font-size: 80%;
        color: #ccc
    }

    .daterangepicker select.monthselect, .daterangepicker select.yearselect {
        font-size: 12px;
        padding: 1px;
        height: auto;
        margin: 0;
        cursor: default
    }

    .daterangepicker select.monthselect {
        margin-right: 2%;
        width: 56%
    }

    .daterangepicker select.yearselect {
        width: 40%
    }

    .daterangepicker select.ampmselect, .daterangepicker select.hourselect, .daterangepicker select.minuteselect, .daterangepicker select.secondselect {
        width: 50px;
        margin-bottom: 0
    }

    .daterangepicker th.month {
        width: auto
    }

    .daterangepicker .input-mini {
        display: block;
        width: 100%;
        padding: .375rem .75rem .375rem 1.6875rem;
        font-size: .875rem;
        line-height: 1.5;
        color: #3e515b;
        background-color: #fff;
        background-image: none;
        background-clip: padding-box;
        border: 1px solid #e3e8ec;
        border-radius: 0;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out
    }

    .daterangepicker .input-mini::-ms-expand {
        background-color: transparent;
        border: 0
    }

    .daterangepicker .input-mini:focus {
        color: #3e515b;
        background-color: #fff;
        border-color: #8ad4ee;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(32, 168, 216, .25)
    }

    .daterangepicker .input-mini::-webkit-input-placeholder {
        color: #94a0b2;
        opacity: 1
    }

    .daterangepicker .input-mini:-ms-input-placeholder {
        color: #94a0b2;
        opacity: 1
    }

    .daterangepicker .input-mini::-ms-input-placeholder {
        color: #94a0b2;
        opacity: 1
    }

    .daterangepicker .input-mini::placeholder {
        color: #94a0b2;
        opacity: 1
    }

    .daterangepicker .input-mini:disabled, .daterangepicker .input-mini[readonly] {
        background-color: #e3e8ec;
        opacity: 1
    }

    .daterangepicker .input-mini.active {
        border: 1px solid #20a8d8
    }

    .daterangepicker .daterangepicker_input i {
        position: absolute;
        left: 8px;
        top: 10px;
        color: #e3e8ec
    }

    .daterangepicker .daterangepicker_input {
        position: relative
    }

    .daterangepicker .calendar-time {
        text-align: center;
        margin: 5px auto;
        line-height: 30px;
        position: relative;
        padding-left: 28px
    }

    .daterangepicker .calendar-time select.disabled {
        color: #ccc;
        cursor: not-allowed
    }

    .daterangepicker .ranges {
        font-size: 11px;
        float: none;
        margin: 4px;
        text-align: left
    }

    .daterangepicker .ranges ul {
        list-style: none;
        margin: 0 auto;
        padding: 0;
        width: 100%
    }

    .daterangepicker .ranges li {
        font-size: 9px;
        background: #f1f3f8;
        border: 1px solid #e3e8ec;
        padding: 3px 3px;
        margin-bottom: 5px;
        cursor: pointer
    }

    .daterangepicker .ranges li.active, .daterangepicker .ranges li:hover {
        background: #20a8d8;
        border-color: #20a8d8;
        color: #fff
    }

    @media (min-width: 564px) {
        .daterangepicker .calendar, .daterangepicker .ranges, .daterangepicker.single .calendar, .daterangepicker.single .ranges {
            float: left
        }

        .daterangepicker {
            width: auto
        }

        .daterangepicker .ranges ul {
            width: 160px
        }

        .daterangepicker.single .ranges ul {
            width: 100%
        }

        .daterangepicker .calendar-table {
            margin-top: 15px;
        }

        .daterangepicker .calendar.left .calendar-table {
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0
        }

        .daterangepicker .calendar.right .calendar-table {
            border-left: none;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0
        }

        .daterangepicker .calendar.left {
            clear: left;
            margin-right: 0
        }

        .daterangepicker.single .calendar.left {
            clear: none
        }

        .daterangepicker .calendar.right {
            margin-left: 0
        }

        .daterangepicker .calendar.left .calendar-table, .daterangepicker .left .daterangepicker_input {
            padding-right: 12px
        }
    }

    @media (min-width: 730px) {
        .daterangepicker .ranges {
            width: auto;
            float: left
        }

        .daterangepicker .calendar.left {
            clear: none
        }
    }

</style>