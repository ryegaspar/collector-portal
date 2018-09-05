<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="id, username, name"></vtable-header>
                        <vtable-sub-header-collectors @addCollector="addCollector">
                        </vtable-sub-header-collectors>
                        <!--<vtable-sub-header-users @addUser="addUser">-->
                        <!--</vtable-sub-header-users>-->
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions">
                                    <!--<button type="button"-->
                                            <!--class="btn btn-sm btn-info"-->
                                            <!--data-toggle="tooltip"-->
                                            <!--data-placement="top"-->
                                            <!--title="Edit"-->
                                            <!--v-if="isNotCurrentUser(props.rowData.id)"-->
                                            <!--@click="itemAction('edit-item', props.rowData, props.rowIndex, $event)">-->
                                        <!--<i class="fa fa-pencil-square-o"></i>-->
                                    <!--</button>-->
                                    <!--<button type="button"-->
                                            <!--class="btn btn-sm"-->
                                            <!--:class="props.rowData.active ? 'btn-danger' : 'btn-success'"-->
                                            <!--data-toggle="tooltip"-->
                                            <!--data-placement="top"-->
                                            <!--:title="props.rowData.active ? 'Deactivate' : 'Activate'"-->
                                            <!--v-if="isNotCurrentUser(props.rowData.id)"-->
                                            <!--@click="itemAction('toggle-active', props.rowData, props.rowIndex, $event)">-->
                                        <!--<i :class="props.rowData.active ? 'fa fa-thumbs-down' : 'fa fa-thumbs-up'"></i>-->
                                    <!--</button>-->
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <modal-collector-create ref="modalCollector"
                                :isAdd="isAdd">
        </modal-collector-create>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableUsersFieldDefs from './VtableAdminsFieldDefs';
	// import Vtable from '../VTable';
	import VueEvents from 'vue-events';
	import VtableSubHeaderCollectors from './VtableSubHeaderCollectors';
	import ModalCollectorCreate from './ModalCollectorCreate';

	Vue.use(VueEvents);

	export default {

		components: {
			// Vtable,
			VtableHeader,
            VtableSubHeaderCollectors,
			// VtableSubHeaderUsers,
            ModalCollectorCreate
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
				moreParams: {},
				perPage: 25,

				isAdd: true,
				formData: '',
			}
		},
        //
		methods: {
			addCollector() {
				this.isAdd = true;
				this.$refs.modalCollector.resetModal();
				$("#modalCollectorCreate").modal("show");
            }
		// 	addUser() {
		// 		this.isAdd = true;
		// 		this.$refs.modalUser.resetModal();
		// 		$("#modalUser").modal("show");
		// 	},
        //
		// 	formSubmitted() {
		// 		this.$emit('reload');
		// 	},
        //
		// 	itemAction(action, data, index, e) {
		// 		let innerHTML = e.currentTarget.innerHTML;
		// 		let button = e.currentTarget;
        //
		// 		$('[data-toggle="tooltip"]').tooltip('hide');
        //
		// 		button.setAttribute("disabled", true);
		// 		button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;
        //
		// 		if (action === 'edit-item') {
		// 			this.isAdd = false;
		// 			let url = `/admin/users/${data.id}/edit`;
		// 			axios.get(url)
		// 				.then(({data}) => {
		// 					$("#modalUser").modal("show");
		// 					this.$refs.modalUser.populateData(data);
        //
		// 					button.removeAttribute("disabled");
		// 					button.innerHTML = innerHTML;
		// 				});
        //
		// 			return;
		// 		}
        //
		// 		swal({
		// 			title: "Change user status",
		// 			text: `Are you sure you want to change the status of ${data.full_name}`,
		// 			icon: "warning",
		// 			buttons: true,
		// 			dangerMode: true
		// 		}).then((willChange) => {
		// 			if (willChange) {
		// 				axios.patch(`./users/${data.id}/toggle-active`)
		// 					.then(() => {
		// 						button.removeAttribute("disabled");
		// 						button.innerHTML = innerHTML;
        //
		// 						if (button.childNodes[0].className === 'fa fa-thumbs-up')
		// 							button.childNodes[0].className = 'fa fa-thumbs-down';
		// 						else
		// 							button.childNodes[0].className = 'fa fa-thumbs-up';
        //
		// 						this.$emit('reload');
        //
		// 						lib.swalSuccess("Updated status of the user");
		// 					})
		// 					.catch((error) => {
		// 						lib.swalError(error.message);
        //
		// 						button.removeAttribute("disabled");
		// 						button.innerHTML = innerHTML;
		// 					});
		// 			} else {
		// 				button.removeAttribute("disabled");
		// 				button.innerHTML = innerHTML;
		// 			}
		// 		})
		// 	},
        //
		// 	isNotCurrentUser(userId) {
		// 		return Number(window.App.userId) !== Number(userId);
		// 	}
        //
		},
        //
		// computed: {
		// 	tableUrl() {
		// 		return `/admin/users`;
		// 	},
		// },
        //
		// mounted() {
		// }
	}
</script>