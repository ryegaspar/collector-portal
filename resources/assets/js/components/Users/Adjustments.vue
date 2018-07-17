<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="filter-bar">
                           <div class="form-inline">
                               <button type="button" class="btn btn-primary mb-2 mr-2" @click="addAdjustment">
                                   <i class="icon-plus"></i> Add
                               </button>
                               <button type="button" class="btn btn-primary mb-2" @click="editAdjustment">
                                   <i class="icon-pencil"></i> Edit
                               </button>
                           </div>
                        </div>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                            <template slot="actions" slot-scope="props">
                                <div class="custom-actions">
                                    <button class="btn btn-success btn-sm"
                                            data-toggle="tooltip"
                                            title="edit"
                                            @click="itemAction('edit-item', props.rowData, props.rowIndex)">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                            data-toggle="tooltip"
                                            title="delete"
                                            @click="itemAction('delete-item', props.rowData, props.rowIndex)">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <modal-adjustments :isAdd="isAdd"></modal-adjustments>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableAccountsFieldDefs from './VtableAccountsFieldDefs';
	import Vtable from '../VTable';
	import VueEvents from 'vue-events';
	import ModalAdjustments from './ModalAdjustments';

	Vue.use(VueEvents);

	Vue.component('vtable-header', VtableHeader);

	export default {

		components: {
			Vtable,
            ModalAdjustments
		},

		data() {
			return {
				fieldDefs: VtableAccountsFieldDefs,
				sortOrder: [
					{
						field: 'last_worked',
						sortField: 'DBR_LAST_WORKED_O',
						direction: 'desc'
					}
				],
				moreParams: {},
				perPage: 25,

                isAdd: true
			}
		},

        methods: {
			addAdjustment() {
				this.isAdd = true;
				$("#modalAdjustment").modal("show");
            },

            editAdjustment() {
				this.isAdd = false;
				$("#modalAdjustment").modal("show");
            }
        },

		computed: {
			tableUrl() {
				return `./accounts/show`;
			}
		}
	}
</script>