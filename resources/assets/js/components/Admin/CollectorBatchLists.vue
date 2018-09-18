<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-sub-header-collector-batch-lists :id="id">
                        </vtable-sub-header-collector-batch-lists>
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
    </div>
</template>

<script>
	import VtableCollectorBatchListsFieldDefs from './VtableCollectorBatchListsFieldDefs';
	import Vtable from '../VTable';
	import VtableSubHeaderCollectorBatchLists from './VtableSubHeaderCollectorBatchLists';

	export default {

		components: {
			Vtable,
			VtableSubHeaderCollectorBatchLists,
        },

		props: ['id'],

		data() {
			return {
				fieldDefs: VtableCollectorBatchListsFieldDefs,
				sortOrder: [
					{
						field: 'id',
						sortField: 'id',
						direction: 'asc'
					}
				],
				moreParams: {},
				perPage: 500,
			}
		},

		methods: {

        },

		computed: {
			tableUrl() {
				return `/admin/collector-batches/${this.id}/list`;
			},
		},
	}
</script>