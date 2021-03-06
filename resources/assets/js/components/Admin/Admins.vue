<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="id, username, name"></vtable-header>
                        <vtable-sub-header-admins @addUser="addUser">
                        </vtable-sub-header-admins>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions">
                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit"
                                            v-if="isNotCurrentUser(props.rowData.id)"
                                            @click="itemAction('edit-item', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-warning"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Toggle Active"
                                            v-if="isNotCurrentUser(props.rowData.id)"
                                            @click="itemAction('toggle-active', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-exchange"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <admin-modal ref="adminModal"
                    :isAdd="isAdd"
                    :formData="formData"
                    @submitted="formSubmitted">
        </admin-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableAdminsFieldDefs from './VtableAdminsFieldDefs';
	import Vtable from '../VTable';
	import VtableSubHeaderAdmins from './VtableSubHeaderAdmins';
	import AdminModal from './AdminModal'

	export default {

		components: {
			Vtable,
			VtableHeader,
            VtableSubHeaderAdmins,
            AdminModal
		},

		data() {
			return {
				fieldDefs: VtableAdminsFieldDefs,
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

		methods: {
			addUser() {
				this.isAdd = true;
				this.$refs.adminModal.resetModal();
				$("#adminModal").modal("show");
            },

            formSubmitted() {
				this.$emit('reload');
            },

			itemAction(action, data, index, e) {
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

				if (action === 'edit-item') {
					this.isAdd = false;

					this.$refs.adminModal.populateData(data);
					$("#adminModal").modal("show");

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

                    return;
                }

				swal({
					title: "Change user status",
					text: `Are you sure you want to change the status of ${data.full_name}`,
					icon: "warning",
					buttons: true,
                    dangerMode: true
				}).then((willChange) => {
					if (willChange) {
						axios.patch(`./admins/${data.id}/toggle-active`)
							.then(() => {
								button.removeAttribute("disabled");
								button.innerHTML = innerHTML;

								// if (button.childNodes[0].className === 'fa fa-thumbs-up')
								// 	button.childNodes[0].className = 'fa fa-thumbs-down';
								// else
								// 	button.childNodes[0].className = 'fa fa-thumbs-up';

								this.$emit('reload');

								lib.swalSuccess("Updated status of the user");
							})
							.catch((error) => {
								if (error.response.status === 405) {
									lib.swalError(error.response.data.message);

									button.removeAttribute("disabled");
									button.innerHTML = innerHTML;

									return;
                                }

								lib.swalError(error.message);

								button.removeAttribute("disabled");
								button.innerHTML = innerHTML;
							});
					} else {
						button.removeAttribute("disabled");
						button.innerHTML = innerHTML;
                    }
				})
			},

			isNotCurrentUser(userId) {
				return Number(window.App.userId) !== Number(userId);
			}

		},

		computed: {
			tableUrl() {
				return `/admin/admins`;
			},
		},
	}
</script>