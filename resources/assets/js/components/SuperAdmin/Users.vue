<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name, id"></vtable-header>
                        <vtable-sub-header-users @addUser="addUser"></vtable-sub-header-users>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions">
                                    <!--<button type="button" class="btn btn-sm"-->
                                            <!--:class="props.rowData.status==0 ? 'btn-info' : 'btn-success'"-->
                                            <!--data-toggle="tooltip"-->
                                            <!--data-placement="top"-->
                                            <!--:title="props.rowData.status==0 ? 'review' : 're-review'"-->
                                            <!--@click="itemAction('review-item', props.rowData, props.rowIndex, $event)">-->
                                        <!--<i class="fa fa-pencil-square-o"></i>-->
                                    <!--</button>-->
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableUsersFieldDefs from './VtableUsersFieldDefs';
	import Vtable from '../VTable';
	import VueEvents from 'vue-events';
	import VtableSubHeaderUsers from './VtableSubHeaderUsers';

	Vue.use(VueEvents);

	export default {

		components: {
			Vtable,
			VtableHeader,
            VtableSubHeaderUsers
		},

		data() {
			return {
				fieldDefs: VtableUsersFieldDefs,
				sortOrder: [
					{
						field: 'created_at',
						sortField: 'created_at',
						direction: 'desc'
					}
				],
				moreParams: {
					// 'date': this.startDate() + '|' + this.endDate()
				},
				perPage: 25,

				// isAdd: true
			}
		},

		methods: {
			addUser() {
				console.log('gotcha!');
            }
			// startDate() {
			// 	return moment(moment().startOf('month')).format("YYYY-MM-DD");
			// },
            //
			// endDate() {
			// 	return moment(moment().endOf('month')).format("YYYY-MM-DD");
			// },
            //
			// itemAction(action, data, index, e) {
			// 	let innerHTML = e.currentTarget.innerHTML;
			// 	let button = e.currentTarget;
            //
			// 	$('[data-toggle="tooltip"]').tooltip('hide');
            //
			// 	button.setAttribute("disabled", true);
			// 	button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`
            //
			// 	swal({
			// 		title: `Review Adjustment`,
			// 		text: `What do you think with ${data.desk} - ${data.dbr_no} adjustment?`,
			// 		icon: "info",
			// 		buttons: {
			// 			Cancel: true,
			// 			Deny: true,
			// 			Approve: true,
			// 		}
			// 	}).then((action) => {
			// 		switch (action) {
			// 			case "Deny":
			// 				axios.patch(`./adjustments/${data.id}`, {'status': 2})
			// 					.then(() => {
			// 						swal({
			// 							title: "Success",
			// 							text: "Success - Denied Adjustment!",
			// 							icon: "success",
			// 							timer: 1000
			// 						});
			// 						this.$emit('reload');
			// 					});
			// 				break;
			// 			case "Approve":
			// 				axios.patch(`./adjustments/${data.id}`, {'status': 1})
			// 					.then(() => {
			// 						swal({
			// 							title: "Success",
			// 							text: "Success - Approve Adjustment!",
			// 							icon: "success",
			// 							timer: 1000
			// 						});
			// 						this.$emit('reload');
			// 					});
			// 				break;
			// 			default:
			// 		}
            //
			// 		button.removeAttribute("disabled");
			// 		button.innerHTML = innerHTML;
			// 	})
			// }
		},

		computed: {
			tableUrl() {
				return `./users/show`;
			},

			// startText() {
			// 	return moment().startOf('month');
			// },
            //
			// endText() {
			// 	return moment().endOf('month');
			// }
		},

		mounted() {
		}
	}
</script>