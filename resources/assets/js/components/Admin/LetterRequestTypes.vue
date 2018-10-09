<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="id, name"></vtable-header>
                        <vtable-sub-header-letter-request-types @addLetterRequestType="addLetterRequestType">
                        </vtable-sub-header-letter-request-types>
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
                                            @click="itemAction('edit-item', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-warning"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Toggle Status"
                                            @click="itemAction('toggle-status', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-exchange"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <letter-request-type-modal
                ref="letterRequestTypeModal"
                :isAdd="isAdd"
                @submitted="formSubmitted">
        </letter-request-type-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableLetterRequestTypesFieldDefs from './VtableLetterRequestTypesFieldDefs';
	import VtableSubHeaderLetterRequestTypes from './VtableSubHeaderLetterRequestTypes';
	import LetterRequestTypeModal from './LetterRequestTypeModal';
	import Vtable from '../VTable';

	export default {

		components: {
			VtableHeader,
			VtableSubHeaderLetterRequestTypes,
			LetterRequestTypeModal,
			Vtable,
		},

		data() {
			return {
				fieldDefs: VtableLetterRequestTypesFieldDefs,
				sortOrder: [
					{
						field: 'id',
						sortField: 'id',
						direction: 'asc'
					}
				],
				moreParams: {},
				perPage: 25,

				isAdd: true,
			}
		},

		methods: {
			addLetterRequestType() {
				this.isAdd = true;
				this.$refs.letterRequestTypeModal.resetModal();
				$("#letterRequestTypeModal").modal("show");
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
					let url = `/admin/letter-request-type/${data.id}/edit`;
					axios.get(url)
						.then(({data}) => {
							$("#letterRequestTypeModal").modal("show");
							this.$refs.letterRequestTypeModal.populateData(data);

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
					title: "Change Letter Request Type Status",
					text: `Are you sure you want to change the active status of ${data.name}?`,
					icon: "warning",
					buttons: true,
					dangerMode: true
				}).then((willChange) => {
					if (willChange) {
						axios.patch(`/admin/letter-request-type/${data.id}/toggle-active`)
							.then(() => {
								button.removeAttribute("disabled");
								button.innerHTML = innerHTML;

								this.$emit('reload');

								lib.swalSuccess("Updated status of the Letter Request Type");
							})
							.catch((error) => {
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
		},

		computed: {
			tableUrl() {
				return `/admin/letter-request-type`;
			},
		},
	}
</script>