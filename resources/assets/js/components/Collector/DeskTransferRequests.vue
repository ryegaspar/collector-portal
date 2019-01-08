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
                                            class="btn btn-sm btn-info"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit"
                                            @click="itemAction('edit-item', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status==='0' &&
                                                                props.rowData.requestable_type==='App\\Models\\Lynx\\Collector' &&
                                                                +props.rowData.requestable_id===+collectorId">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="delete"
                                            @click="itemAction('delete-item', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status==='0' &&
                                                                props.rowData.requestable_type==='App\\Models\\Lynx\\Collector' &&
                                                                +props.rowData.requestable_id===+collectorId">
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
    </div>
</template>

<script>
	import VtableDeskTransferRequestsFieldDefs from './VtableDeskTransferRequestsFieldDefs';
	import Vtable from '../VTable';
	import VtableSubHeaderDeskTransferRequests from './VtableSubHeaderDeskTransferRequests';
	import DeskTransferRequestModal from './DeskTransferRequestModal';
	import DeskTransferRequestNotesModal from './DeskTransferRequestNotesModal';
	import CollectorOptionStore from './Store';

	export default {

		store: CollectorOptionStore,

		components: {
			Vtable,
            VtableSubHeaderDeskTransferRequests,
			DeskTransferRequestModal,
			DeskTransferRequestNotesModal,
		},

		data() {
			return {
				collectorId: window.App.userId,

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

				isAdd: true
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
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

				if (action === 'show-notes') {
					this.$refs.deskTransferRequestNotesModal.populateData(data.notes);
					$("#deskTransferRequestNotesModal").modal("show");

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}

				if (action === 'edit-item') {
					this.isAdd = false;

					$("#deskTransferRequestModal").modal("show");
					this.$refs.deskTransferRequestModal.populateData(data);

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

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
						axios.delete(`./desk-transfer-requests/${data.id}`)
							.then(() => {
								lib.swalSuccess("Successfully deleted desk transfer request");
								this.$emit('reload');
							})
							.catch((error) => {
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
				return `/desk-transfer-requests?filter2=${this.collectorId}`;
			},
		},
	}
</script>