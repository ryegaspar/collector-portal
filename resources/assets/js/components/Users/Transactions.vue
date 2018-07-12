<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="name, id"></vtable-header>
                        <vtable-sub-header-transactions :prop-start-date="startText"
                                        :prop-end-date="endText">
                        </vtable-sub-header-transactions>
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
	import VtableSubHeaderTransactions from './VtableSubHeaderTransactions';
	import VtableTransactionsFieldDefs from './VtableTransactionsFieldDefs';
	import Vtable from '../VTable';
	import VueEvents from 'vue-events';

	Vue.use(VueEvents);

	Vue.component('vtable-header', VtableHeader);

	export default {

		components: {
			Vtable,
            VtableSubHeaderTransactions
		},

		data() {
			return {
				fieldDefs: VtableTransactionsFieldDefs,
				sortOrder: [
					{
						field: 'pay_date',
						sortField: 'PAY_DATE_O',
						direction: 'asc'
					}
				],
				moreParams: {
					'paydate': this.startDate() + '|' + this.endDate()
                },
				perPage: 25
			}
		},

        methods: {
			startDate() {
				return moment(moment().add(-1, 'days')).format("YYYY-MM-DD");
			},

			endDate() {
				return moment(moment().add(3, 'days')).format("YYYY-MM-DD");
			},
        },

		computed: {
			tableUrl() {
				return `./transactions/show`;
			},

            startText() {
				return moment().add(-1, 'days');
            },

            endText() {
				return moment().add(3, 'days');
            }
		}
	}
</script>