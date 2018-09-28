<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="id, title, author"></vtable-header>
                        <!--<vtable-sub-header-users @addUser="addUser">-->
                        <!--</vtable-sub-header-users>-->
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions">
                                    <button type="button"
                                            class="btn btn-sm btn-success"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Preview"
                                            @click="itemAction('show-preview', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-warning"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Publish"
                                            v-if="props.rowData.published_at === 'Never'"
                                            @click="itemAction('publish-script', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit"
                                            @click="itemAction('edit-script', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete"
                                            @click="itemAction('delete-script', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <script-modal ref="scriptModal"></script-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableScriptsFieldDefs from './VtableScriptsFieldDefs';
	import Vtable from '../VTable';
	import ScriptModal from './ScriptModal'

	export default {
		components: {
			Vtable, VtableHeader, ScriptModal
		},

		data() {
			return {
				fieldDefs: VtableScriptsFieldDefs,
				sortOrder: [
					{
						field: 'updated_at',
						sortField: 'updated_at',
						direction: 'desc'
					}
				],
				moreParams: {},
				perPage: 25,
			}
		},

		methods: {
			itemAction(action, data, index, e) {
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

				if (action === 'show-preview') {
					this.$refs.scriptModal.loadPreview(`/admin/scripts/${data.id}`);
					$("#scriptModal").modal("show");

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}

				if (action === 'publish-script') {
					swal({
						title: "Publish",
						text: `Are you sure you want to publish this script`,
						icon: "warning",
						buttons: true,
						dangerMode: true
					}).then((value) => {
						if (value) {
							axios.patch(`/admin/scripts/${data.id}/publish`)
								.then(() => {
									lib.swalSuccess("Successfully published script");

									this.$emit('reload');
								}).catch((error) => {
									lib.swalError("Unable to publish the script");
							});
						}
						button.removeAttribute("disabled");
						button.innerHTML = innerHTML;
					});

					return;
				}

				if (action === 'edit-script') {
					window.location.href = `/admin/scripts/${data.id}/edit`;
					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}

				swal({
					title: "Delete Script",
					text: `Are you sure you want to delete this script?`,
					icon: "warning",
					buttons: true,
					dangerMode: true
				}).then((response) => {
					if (response) {
						axios.delete(`/admin/scripts/${data.id}`)
                            .then(() => {
                            	lib.swalSuccess("Successfully deleted script");

								this.$emit('reload');
                            })
                            .catch((error) => {
                            	lib.swalError(error.message);
                            });
                    }

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;
				});
			},
		},

		computed: {
			tableUrl() {
				return `/admin/scripts`;
			},
		},
	}
</script>