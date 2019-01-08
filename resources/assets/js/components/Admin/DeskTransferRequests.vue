<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name, dbr no"></vtable-header>
                        <vtable-sub-header-desk-transfer-requests
                                @addDeskTransferRequest="addDeskTransferRequest"></vtable-sub-header-desk-transfer-requests>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions" style="white-space: nowrap !important">
                                    <button type="button"
                                            class="btn btn-sm btn-purple"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Notes"
                                            @click="itemAction('show-notes', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-sticky-note-o"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-success"
                                            @click="itemAction('approve-item', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status==='0' || props.rowData.status==='2'">
                                        <i class="fa fa-thumbs-up"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-red"
                                            @click="itemAction('deny-item', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status==='0' || props.rowData.status==='1'">
                                        <i class="fa fa-thumbs-down"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit"
                                            @click="itemAction('edit-item', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status==='0' &&
                                                props.rowData.requestable_type==='App\\Models\\Lynx\\Admin' &&
                                                +props.rowData.requestable_id===+adminId">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="delete"
                                            @click="itemAction('delete-item', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status==='0' &&
                                                props.rowData.requestable_type==='App\\Models\\Lynx\\Admin' &&
                                                +props.rowData.requestable_id===+adminId">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <desk-transfer-request-modal :isAdd="isAdd"
                              @submitted="formSubmitted"
                              ref="deskTransferRequestModal">
        </desk-transfer-request-modal>
        <desk-transfer-request-notes-modal ref="deskTransferRequestNotesModal"></desk-transfer-request-notes-modal>
        <desk-transfer-request-reject-reason-modal :rejectedId="rejectedId" @submitted="formSubmitted"></desk-transfer-request-reject-reason-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableDeskTransferRequestsFieldDefs from '../Collector/VtableDeskTransferRequestsFieldDefs';
	import Vtable from '../VTable';
	import DeskTransferRequestModal from './DeskTransferRequestModal';
	import DeskTransferRequestNotesModal from '../Collector/DeskTransferRequestNotesModal';
	import DeskTransferRequestRejectReasonModal from './DeskTransferRequestRejectReasonModal';
	import CollectorOptionStore from './Store';
	import VtableSubHeaderDeskTransferRequests from './VtableSubHeaderDeskTransferRequests';

	export default {

		store: CollectorOptionStore,

		components: {
			Vtable,
			VtableHeader,
			VtableSubHeaderDeskTransferRequests,
			DeskTransferRequestModal,
			DeskTransferRequestNotesModal,
            DeskTransferRequestRejectReasonModal,
		},

		data() {
			return {
				adminId: window.App.userId,
                rejectedId: '',

				fieldDefs: VtableDeskTransferRequestsFieldDefs,

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

                targetButton: '',
			}
		},

		methods: {
			addDeskTransferRequest() {
				this.isAdd = true;
				this.$refs.deskTransferRequestModal.resetModal();
				$("#deskTransferRequestModal").modal("show");
			},

			formSubmitted() {
				this.$emit('reload');
			},

			itemAction(action, data, index, e) {
				$('[data-toggle="tooltip"]').tooltip('hide');

				if (action === 'show-notes') {
					this.$refs.deskTransferRequestNotesModal.populateData(data.notes);
					$("#deskTransferRequestNotesModal").modal("show");

					return;
				}

				if (action === 'approve-item') {
					axios.patch(`/admin/desk-transfer-requests/${data.id}/approve`)
						.then(({data}) => {
							lib.swalSuccess("Successfully changed the status of desk transfer request");

							this.$emit('reload');
						})
						.catch((error) => {
							lib.swalError(error.message);
						});

					return;
				}

				if (action === 'deny-item') {
					this.rejectedId = data.id;

					$("#deskTransferRequestRejectReasonModal").modal("show");

					return;
				}

				if (action === 'edit-item') {
					this.isAdd = false;
					let url = `/admin/desk-transfer-requests/${data.id}/edit`;

					this.$refs.deskTransferRequestModal.populateData(data);
					$("#deskTransferRequestModal").modal("show");

					return;
				}

				swal({
					title: "Are you sure?",
					text: "You will not be able to recover this data",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if (willDelete) {
						axios.delete(`/admin/desk-transfer-requests/${data.id}`)
							.then(() => {
								lib.swalSuccess("Successfully deleted desk transfer request");
								this.$emit('reload');
							})
							.catch((error) => {
								lib.swalError(error.message);
							});
					}
				})
			}
		},

		computed: {
			tableUrl() {
				return `/admin/desk-transfer-requests`;
			},
		},
	}
</script>