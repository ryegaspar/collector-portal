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
                                            @click="showPreview(props.rowData.id)">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit"
                                            v-if=""
                                            @click="">
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
    		showPreview(id) {
    			this.$refs.modalScript.loadPreview(id);
				$("#modalScript").modal("show");
            }
        },

		computed: {
			tableUrl() {
				return `/admin/scripts`;
			},
		},

    }
</script>