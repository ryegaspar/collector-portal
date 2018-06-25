<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <filter-bar :fields="fieldDefs" placeholder="name, id"></filter-bar>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=50>
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
    </div>
</template>

<script>
	import FilterBar from '../FilterBar';
	import VtableCollectionFieldDefs from './VtableCollectionFieldDefs';
    import Vtable from '../VTable';
    import VueEvents from 'vue-events';

    Vue.use(VueEvents);

	Vue.component('filter-bar', FilterBar);

	export default {

		components: {
			Vtable
        },

		data() {
			return {
				fieldDefs: VtableCollectionFieldDefs,
                sortOrder: [
                    {
                    	field: 'DBR_LAST_TRUST_DATE_O',
                        sortField: 'DBR_LAST_TRUST_DATE_O',
                        direction: 'desc'
                    }
                ],
                moreParams: {}
			}
        },

        computed: {
			tableUrl() {
				return `./collections/show`;
            }
        }
    }
</script>