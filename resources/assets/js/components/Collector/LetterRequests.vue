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
                                                @click="addLetterRequest">
                                            <i class="icon-plus"></i> New
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name, dbr no"></vtable-header>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions">
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
        <letter-request-modal :isAdd="isAdd"
                              @submitted="formSubmitted"
                              ref="letterRequestModal">
        </letter-request-modal>
        <letter-request-notes-modal ref="letterRequestNotesModal"></letter-request-notes-modal>
    </div>
</template>

<script>
	import VtableLetterRequestsFieldDefs from './VtableLetterRequestsFieldDefs';
	import Vtable from '../VTable';
	import LetterRequestModal from './LetterRequestModal';
	import LetterRequestNotesModal from './LetterRequestNotesModal';
	import CollectorOptionStore from './Store';

	export default {

		store: CollectorOptionStore,

		components: {
			Vtable,
			LetterRequestModal,
			LetterRequestNotesModal,
		},

		data() {
			return {
				collectorId: window.App.userId,

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
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

				if (action === 'show-notes') {
					this.$refs.letterRequestNotesModal.populateData(data.notes);
					$("#letterRequestNotesModal").modal("show");

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}

				if (action === 'edit-item') {
					this.isAdd = false;
					let url = `letter-requests/${data.id}/edit`;
					axios.get(url)
						.then(({data}) => {
							$("#letterRequestModal").modal("show");
							this.$refs.letterRequestModal.populateData(data);

							button.removeAttribute("disabled");
							button.innerHTML = innerHTML;
						})
						.catch((error) => {
							lib.swalError(error.message);

							button.removeAttribute("disabled");
							button.innerHTML = innerHTML;
						});

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
						axios.delete(`./letter-requests/${data.id}`)
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
				return `/letter-requests`;
			},
		},
	}
</script>