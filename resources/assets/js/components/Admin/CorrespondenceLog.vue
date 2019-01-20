<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="client_name"></vtable-header>
                        <vtable-sub-header-correspondence-log
                                @addCorrespondenceLog="addCorrespondenceLog"></vtable-sub-header-correspondence-log>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions" style="white-space: nowrap !important">
                                    <button type="button"
                                            class="btn btn-sm btn-dark"
											title="Update Status"
                                            @click="itemAction('updatestatus', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status < '2'">
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
                                            class="btn btn-sm btn-light"
											title="Download Correspondence"
                                            @click="itemAction('downloadfile', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.attachment_name > '0'">
                                        <i class="fa fa-cloud-download"></i>
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
        <correspondence-log-modal :isAdd="isAdd"
							  :unifinclients="unifinclients"
                              @submitted="formSubmitted"
                              ref="correspondenceLogModal">
        </correspondence-log-modal>
        <correspondence-log-notes-modal ref="correspondenceLogNotesModal"></correspondence-log-notes-modal>
        <correspondence-log-reject-reason-modal :rejectedId="rejectedId" @submitted="formSubmitted"></correspondence-log-reject-reason-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableCorrespondenceLogFieldDefs from '../Admin/VtableCorrespondenceLogFieldDefs';
	import Vtable from '../VTable';
	import CorrespondenceLogModal from './CorrespondenceLogModal';
	import CorrespondenceLogNotesModal from '../Admin/CorrespondenceLogNotesModal';
	import CorrespondenceLogRejectReasonModal from './CorrespondenceLogRejectReasonModal';
	import CollectorOptionStore from './Store';
	import VtableSubHeaderCorrespondenceLog from './VtableSubHeaderCorrespondenceLog';

	export default {
		props: [
            'unifinclients'
		],


		store: CollectorOptionStore,

		components: {
			Vtable,
			VtableHeader,
			VtableSubHeaderCorrespondenceLog,
			CorrespondenceLogModal,
			CorrespondenceLogNotesModal,
            CorrespondenceLogRejectReasonModal,
		},

		data() {
			return {
				adminId: window.App.userId,
                rejectedId: '',

				fieldDefs: VtableCorrespondenceLogFieldDefs,

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
			addCorrespondenceLog() {
				this.isAdd = true;
				this.$refs.correspondenceLogModal.resetModal();
				$("#correspondenceLogModal").modal("show");
			},

			formSubmitted() {
				this.$emit('reload');
			},

			itemAction(action, data, index, e) {
				$('[data-toggle="tooltip"]').tooltip('hide');

				if (action === 'show-notes') {
					this.$refs.correspondenceLogNotesModal.populateData(data.notes);
					$("#correspondenceLogNotesModal").modal("show");

					return;
				}

				if (action === 'updatestatus') {
					axios.patch(`/admin/correspondence-log/${data.id}/updatestatus`)
						.then(({data}) => {
							lib.swalSuccess("Status has been updated Sucessfully");

							this.$emit('reload');
						})
						.catch((error) => {
							lib.swalError(error.message);
						});

					return;
				}

				if (action === 'downloadfile') {
					window.open(`/admin/correspondence-log/${data.attachment_path}/${data.attachment_name}`);
					return;
				}

				if (action === 'add-notes') {
					this.rejectedId = data.id;

					$("#correspondenceLogRejectReasonModal").modal("show");

					return;
				}

				if (action === 'edit-item') {
					this.isAdd = false;
					let url = `/admin/correspondence-log/${data.id}/edit`;

					this.$refs.correspondenceLogModal.populateData(data);
					$("#correspondenceLogModal").modal("show");

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
						axios.delete(`/admin/correspondence-log/${data.id}`)
							.then(() => {
								lib.swalSuccess("Successfully deleted correspondence log entry");
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
				return `/admin/correspondence-log`;
			},
		},
	}
</script>