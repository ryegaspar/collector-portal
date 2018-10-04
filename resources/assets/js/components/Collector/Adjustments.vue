<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="filter-bar">
                            <div class="form-inline">
                                <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                                    <div class="btn-group-sm">
                                        <button type="button"
                                                class="btn btn-primary mr-2"
                                                @click="addAdjustment">
                                            <i class="icon-plus"></i> Add
                                        </button>
                                    </div>
                                    <div class="btn-group-sm">
                                       <span style="font-size: 0.75rem;margin-bottom: 0px !important;">
                                           <em>adjustments for {{ startMonthWord }} to {{ endMonthWord }}, deadline: {{ deadlineWord }}</em>
                                       </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name, id"></vtable-header>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions">
                                    <button v-if="props.rowData.status==='0'"
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="delete"
                                            @click="itemAction('delete-item', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <adjustment-modal :isAdd="isAdd"></adjustment-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableAdjustmentsFieldDefs from './VtableAdjustmentsFieldDefs';
	import Vtable from '../VTable';
	import VueEvents from 'vue-events';
	import AdjustmentModal from './AdjustmentModal';

	Vue.use(VueEvents);

	Vue.component('vtable-header', VtableHeader);

	export default {

		components: {
			Vtable,
			AdjustmentModal
		},

		data() {
			return {
				fieldDefs: VtableAdjustmentsFieldDefs,
				sortOrder: [
					{
						field: 'created_at',
						sortField: 'created_at',
						direction: 'desc'
					}
				],
				moreParams: {},
				perPage: 25,

				isAdd: true
			}
		},

		methods: {
			addAdjustment() {
				this.isAdd = true;
				this.$events.fire('modal-reset');
				$("#adjustmentModal").modal("show");
			},

			onReloadTable() {
				this.$emit('reload');
			},

			itemAction(action, data, index, e) {
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`

				swal({
					title: "Are you sure?",
					text: "You will not be able to recover this data",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if (willDelete) {
						axios.delete(`./adjustments/${data.id}`)
							.then(() => {
								lib.swalSuccess("Successfully deleted adjustment data");
								this.$emit('reload');
							}).catch((error) => {
								lib.swalError(error.message);
							});
					}
					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;
				})
			}
		},

		computed: {
			tableUrl() {
				return `./adjustments/show`;
			},

			startMonthWord() {
				if (moment().date() > 5)
					return moment().startOf('month').format("MMMM Do");
				else
					return moment().add(-1, 'month').startOf('month').format("MMMM Do");
			},

			endMonthWord() {
				if (moment().date() > 5)
					return moment().format("MMMM Do");
				else
					return moment().add(-1, 'month').endOf('month').format('MMMM Do');
			},

			deadlineWord() {
				if (moment().date() > 5)
					return moment().add(1, 'month').set('date', 5).format("MMMM D, YYYY")
				else
					return moment().set('date', 5).format("MMMM D, YYYY");
			}
		},

		created() {
			this.$events.$on('reload-table', eventData => this.onReloadTable());
		}
	}
</script>