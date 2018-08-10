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
                                            v-if="!props.rowData.status"
                                            @click="itemAction('publish-script', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit"
                                            v-if=""
                                            @click="itemAction('edit-script', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <!--<button type="button"-->
                                            <!--class="btn btn-sm"-->
                                            <!--:class="props.rowData.active ? 'btn-danger' : 'btn-success'"-->
                                            <!--data-toggle="tooltip"-->
                                            <!--data-placement="top"-->
                                            <!--:title="props.rowData.active ? 'Deactivate' : 'Activate'"-->
                                            <!--v-if="isNotCurrentUser(props.rowData.id)"-->
                                            <!--@click="itemAction('toggle-active', props.rowData, props.rowIndex, $event)">-->
                                        <!--<i :class="props.rowData.active ? 'fa fa-thumbs-down' : 'fa fa-thumbs-up'"></i>-->
                                    <!--</button>-->
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <modal-script ref="modalScript"></modal-script>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableScriptsFieldDefs from './VtableScriptsFieldDefs';
	import Vtable from '../VTable';
	import ModalScript from './ModalScript'

    export default {
    	components: {
    		Vtable, VtableHeader, ModalScript
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
					this.$refs.modalScript.loadPreview(data.id);
					$("#modalScript").modal("show");

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;
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
                            axios.patch(`/admin/scripts/publish/${data.id}`)
                                .then(() => {
									swal({
										title: "Success",
										text: "Successfully published script",
										icon: 'success',
										timer: 1250
									});

									this.$emit('reload');

								}).catch((error) => {
                                    swal({
                                        title: "Error",
                                        text: "Unable to publish the script",
                                        icon: 'error',
                                        timer: 1250
                                    });
                            });
						}
						button.removeAttribute("disabled");
						button.innerHTML = innerHTML;
					});
				}

				if (action === 'edit-script') {
					window.location.assign(`/admin/scripts/${data.id}`);
					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;
                }
			},
        },

		computed: {
			tableUrl() {
				return `/admin/scripts`;
			},
		},

    }
</script>