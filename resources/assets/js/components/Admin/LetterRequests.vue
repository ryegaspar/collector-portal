<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name, dbr no"></vtable-header>
                        <vtable-sub-header-letter-requests
                                @addLetterRequest="addLetterRequest"></vtable-sub-header-letter-requests>
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
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Sent"
                                            @click="itemAction('approve-item', props.rowData, props.rowIndex, $event)"
                                            v-if="props.rowData.status==='0' || props.rowData.status==='2'">
                                        <i class="fa fa-thumbs-up"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-red"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Rejected"
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
        <letter-request-modal :isAdd="isAdd"
                              @submitted="formSubmitted"
                              ref="letterRequestModal">
        </letter-request-modal>
        <letter-request-notes-modal ref="letterRequestNotesModal"></letter-request-notes-modal>
        <letter-request-reject-reason-modal :rejectedId="rejectedId" @submitted="formSubmitted"></letter-request-reject-reason-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableLetterRequestsFieldDefs from '../Collector/VtableLetterRequestsFieldDefs';
	import Vtable from '../VTable';
	import LetterRequestModal from './LetterRequestModal';
	import LetterRequestNotesModal from '../Collector/LetterRequestNotesModal';
	import LetterRequestRejectReasonModal from './LetterRequestRejectReasonModal';
	import CollectorOptionStore from './Store';
	import VtableSubHeaderLetterRequests from './VtableSubHeaderLetterRequests';

	export default {

		store: CollectorOptionStore,

		components: {
			Vtable,
			VtableHeader,
			VtableSubHeaderLetterRequests,
			LetterRequestModal,
			LetterRequestNotesModal,
            LetterRequestRejectReasonModal,
		},

		data() {
			return {
				adminId: window.App.userId,
                rejectedId: '',

				fieldDefs: VtableLetterRequestsFieldDefs,

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

		beforeCreate() {
			this.$store.dispatch('loadLetterRequestType');
		},

		methods: {
			addLetterRequest() {
				this.isAdd = true;
				this.$refs.letterRequestModal.resetModal();
				$("#letterRequestModal").modal("show");
			},

			formSubmitted() {
				this.$emit('reload');
			},

			itemAction(action, data, index, e) {
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`

				if (action === 'show-notes') {
					this.$refs.letterRequestNotesModal.populateData(data.notes);
					$("#letterRequestNotesModal").modal("show");

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}

				if (action === 'approve-item') {
					axios.patch(`/admin/letter-requests/${data.id}/approve`)
						.then(({data}) => {
							lib.swalSuccess("Successfully changed the status of letter request");

							button.removeAttribute("disabled");
							button.innerHTML = `<i class="fa fa-thumbs-down"></i>`;

							this.$emit('reload');
						})
						.catch((error) => {
							lib.swalError(error.message);

							button.removeAttribute("disabled");
							button.innerHTML = innerHTML;
						});

					return;
				}

				if (action === 'deny-item') {
					this.rejectedId = data.id;
					$("#letterRequestRejectReasonModal").modal("show");
					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}

				if (action === 'edit-item') {
					this.isAdd = false;
					let url = `/admin/letter-requests/${data.id}/edit`;

					this.$refs.letterRequestModal.populateData(data);
					$("#letterRequestModal").modal("show");

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
						axios.delete(`/admin/letter-requests/${data.id}`)
							.then(() => {
								lib.swalSuccess("Successfully deleted letter request");
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
				return `/admin/letter-requests`;
			},
		},

		// created() {
		// 	// this.$events.$on('reload-table', eventData => this.onReloadTable());
		// }
	}
</script>