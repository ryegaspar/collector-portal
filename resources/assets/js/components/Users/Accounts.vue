<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name, id"></vtable-header>
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
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableCollectionFieldDefs from './VtableCollectionFieldDefs';
    import Vtable from '../VTable';
    import VueEvents from 'vue-events';

    Vue.use(VueEvents);

	Vue.component('vtable-header', VtableHeader);

	export default {

		components: {
			Vtable
        },

		data() {
			return {
				fieldDefs: VtableCollectionFieldDefs,
                sortOrder: [
                    {
                    	field: 'last_worked',
                        sortField: 'DBR_LAST_WORKED_O',
                        direction: 'desc'
                    }
                ],
                moreParams: {},
                perPage: 25
			}
        },

        computed: {
			tableUrl() {
				return `./accounts/show`;
            }
        }
    }
</script>