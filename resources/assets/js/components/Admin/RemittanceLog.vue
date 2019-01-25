<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="client_name"></vtable-header>
                        <vtable-sub-header-remittance-log
                                @addRemittanceLog="addRemittanceLog"></vtable-sub-header-remittance-log>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions" style="white-space: nowrap !important">
                                    <button type="button"
                                            class="btn btn-sm btn-success"
											title="Complete Report"
                                            @click="itemAction('approve-report', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.report_sent==='0'">
                                        <i class="fa fa-thumbs-up"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-primary"
											title="Complete Remittance"
                                            @click="itemAction('approve-remittance', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.remittance_sent==='0'">
                                        <i class="fa fa-thumbs-up"></i>
                                    </button>
									<button type="button"
                                            class="btn btn-sm btn-dark"
											title="Complete Commission"
                                            @click="itemAction('approve-commission', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.commission_recieved==='0'">
                                        <i class="fa fa-thumbs-up"></i>
                                    </button>
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
											title="Add"
                                            @click="itemAction('add-notes', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
									<button type="button"
                                            class="btn btn-sm btn-primary"
											title="Detailed View"
                                            @click="itemAction('detail-view', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-info"></i>
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
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <remittance-log-modal :isAdd="isAdd"
							  :unifinclients="unifinclients"
                              @submitted="formSubmitted"
                              ref="remittanceLogModal">
        </remittance-log-modal>
        <remittance-log-notes-modal ref="remittanceLogNotesModal"></remittance-log-notes-modal>
        <remittance-log-reject-reason-modal :rejectedId="rejectedId" @submitted="formSubmitted"></remittance-log-reject-reason-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableRemittanceLogFieldDefs from '../Admin/VtableRemittanceLogFieldDefs';
	import Vtable from '../VTable';
	import RemittanceLogModal from './RemittanceLogModal';
	import RemittanceLogNotesModal from '../Admin/RemittanceLogNotesModal';
	import RemittanceLogRejectReasonModal from './RemittanceLogRejectReasonModal';
	import CollectorOptionStore from './Store';
	import VtableSubHeaderRemittanceLog from './VtableSubHeaderRemittanceLog';

	export default {
		props: [
            'unifinclients'
		],


		store: CollectorOptionStore,

		components: {
			Vtable,
			VtableHeader,
			VtableSubHeaderRemittanceLog,
			RemittanceLogModal,
			RemittanceLogNotesModal,
            RemittanceLogRejectReasonModal,
		},

		data() {
			return {
				adminId: window.App.userId,
                rejectedId: '',

				fieldDefs: VtableRemittanceLogFieldDefs,

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
			addRemittanceLog() {
				this.isAdd = true;
				this.$refs.remittanceLogModal.resetModal();
				$("#remittanceLogModal").modal("show");
			},

			formSubmitted() {
				this.$emit('reload');
			},

			itemAction(action, data, index, e) {
				$('[data-toggle="tooltip"]').tooltip('hide');

				if (action === 'show-notes') {
					this.$refs.remittanceLogNotesModal.populateData(data.notes);
					$("#remittanceLogNotesModal").modal("show");

					return;
				}

				if (action === 'approve-report') {
					axios.patch(`/admin/remittance-log/${data.id}/approvereport`)
						.then(({data}) => {
							lib.swalSuccess("Report has been marked as completed");

							this.$emit('reload');
						})
						.catch((error) => {
							lib.swalError(error.message);
						});

					return;
				}

				if (action === 'approve-remittance') {
					axios.patch(`/admin/remittance-log/${data.id}/approveremittance`)
						.then(({data}) => {
							lib.swalSuccess("Remittance has been marked as sent");

							this.$emit('reload');
						})
						.catch((error) => {
							lib.swalError(error.message);
						});

					return;
				}

				if (action === 'approve-commission') {
					axios.patch(`/admin/remittance-log/${data.id}/approvecommission`)
						.then(({data}) => {
							lib.swalSuccess("Commission has been marked as received");

							this.$emit('reload');
						})
						.catch((error) => {
							lib.swalError(error.message);
						});

					return;
				}

				if (action === 'add-notes') {
					this.rejectedId = data.id;

					$("#remittanceLogRejectReasonModal").modal("show");

					return;
				}

				if (action === 'edit-item') {
					this.isAdd = false;
					let url = `/admin/remittance-log/${data.id}/edit`;

					this.$refs.remittanceLogModal.populateData(data);
					$("#remittanceLogModal").modal("show");

					return;
				}

				if (action === 'detail-view') {

					window.open(`/admin/remittance-log/${data.id}`);

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
						axios.delete(`/admin/remittance-log/${data.id}`)
							.then(() => {
								lib.swalSuccess("Successfully deleted remittance log entry");
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
				return `/admin/remittance-log`;
			},
		},
	}
</script>