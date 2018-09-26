<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="username, name, desk"></vtable-header>
                        <vtable-sub-header-collectors @addCollector="addCollector">
                        </vtable-sub-header-collectors>
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
                                            class="btn btn-sm"
                                            :class="props.rowData.active ? 'btn-danger' : 'btn-success'"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            :title="props.rowData.active ? 'Deactivate' : 'Activate'"
                                            @click="itemAction('toggle-active', props.rowData, props.rowIndex, $event)">
                                        <i :class="props.rowData.active ? 'fa fa-thumbs-down' : 'fa fa-thumbs-up'"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <collector-modal ref="collectorModal"
                         :isAdd="isAdd"
                         @submitted="formSubmitted">
        </collector-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableCollectorsFieldDefs from './VtableCollectorsFieldDefs';
	import Vtable from '../VTable';
	import VtableSubHeaderCollectors from './VtableSubHeaderCollectors';
	import CollectorModal from './CollectorModal';

	export default {

		components: {
			Vtable,
			VtableHeader,
			VtableSubHeaderCollectors,
			// VtableSubHeaderUsers,
			CollectorModal
		},

		data() {
			return {
				fieldDefs: VtableCollectorsFieldDefs,
				sortOrder: [
					{
						field: 'start_date',
						sortField: 'start_date',
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
			addCollector() {
				this.isAdd = true;
				this.$refs.collectorModal.resetModal();
				$("#collectorModal").modal("show");
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
					let url = `/admin/collectors/${data.id}/edit`;
					axios.get(url)
						.then(({data}) => {
							$("#collectorModal").modal("show");
							this.$refs.collectorModal.populateData(data);

							button.removeAttribute("disabled");
							button.innerHTML = innerHTML;
						});

					return;
				}

				swal({
					title: "Change collector status",
					text: `Are you sure you want to change the active status of ${data.full_name}?`,
					icon: "warning",
					buttons: true,
					dangerMode: true
				}).then((willChange) => {
					if (willChange) {
						axios.patch(`./collectors/${data.id}/toggle-active`)
							.then(() => {
								button.removeAttribute("disabled");
								button.innerHTML = innerHTML;

								if (button.childNodes[0].className === 'fa fa-thumbs-up')
									button.childNodes[0].className = 'fa fa-thumbs-down';
								else
									button.childNodes[0].className = 'fa fa-thumbs-up';

								this.$emit('reload');

								lib.swalSuccess("Updated status of the collector");
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
				return `/admin/collectors?filter1=1`;
			},
		},
	}
</script>