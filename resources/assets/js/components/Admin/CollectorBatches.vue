<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name"></vtable-header>
                        <vtable-sub-header-collector-batches @newBatch="openModal">
                        </vtable-sub-header-collector-batches>
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
                                            title="View"
                                            @click="itemAction('view-item', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete"
                                            @click="itemAction('delete-item', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>

        <collector-batches-modal ref="collectorBatchesModal" @submitted="submitted"></collector-batches-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableCollectorBatchesFieldDefs from './VtableCollectorBatchesFieldDefs';
	import Vtable from '../VTable';
	import VtableSubHeaderCollectorBatches from './VtableSubHeaderCollectorBatches';
	import CollectorBatchesModal from './CollectorBatchesModal';

	export default {

		components: {
			Vtable,
			VtableHeader,
			VtableSubHeaderCollectorBatches,
			CollectorBatchesModal
		},

		data() {
			return {
				fieldDefs: VtableCollectorBatchesFieldDefs,
				sortOrder: [
					{
						field: 'created_at',
						sortField: 'created_at',
						direction: 'desc'
					}
				],
				moreParams: {},
				perPage: 25,
			}
		},

		methods: {
			openModal() {
				this.$refs.collectorBatchesModal.resetModal();
				$("#collectorBatchesModal").modal("show");
			},

			submitted() {
				this.$emit('reload');
			},

			itemAction(action, data, index, e) {
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

				if (action === 'view-item') {
					window.location.href = `/admin/collector-batches/${data.id}/list`;
					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}

				swal({
					title: "Delete collector batch",
					text: `Are you sure you want to delete ${data.name}? This action also deletes the collector list under it, and is IRREVERSIBLE.`,
					icon: "warning",
					buttons: true,
					dangerMode: true
				}).then((willChange) => {
					if (willChange) {
						axios.delete(`/admin/collector-batches/${data.id}`)
                            .then(() => {
								button.removeAttribute("disabled");
								button.innerHTML = innerHTML;

								this.$emit('reload');

								lib.swalSuccess("Successfully deleted collector batch");
                            })
							.catch((error) => {
								lib.swalError(error.message);

								button.removeAttribute("disabled");
								button.innerHTML = innerHTML;
							});
						button.removeAttribute("disabled");
						button.innerHTML = innerHTML;
					} else {
						button.removeAttribute("disabled");
						button.innerHTML = innerHTML;
					}
				})
			},
		},

		computed: {
			tableUrl() {
				return `/admin/collector-batches`;
			},
		},
	}
</script>