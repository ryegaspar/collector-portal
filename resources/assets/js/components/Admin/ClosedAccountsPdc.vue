<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="dbr #, client"></vtable-header>
                        <vtable :api-url="tableUrl"
                                :fields="fieldDefs"
                                :sort-order="sortOrder"
                                :append-params="moreParams"
                                :perPage=perPage>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import Vtable from '../VTable';
	import VtableSifClosuresFieldDefs from "./VtableSifClosuresFieldDefs";

	export default {

		components: {
			VtableHeader,
			Vtable,
		},

		data() {
			return {
				fieldDefs: VtableSifClosuresFieldDefs,

				sortOrder: [
					{
						field: 'chk_count',
						sortField: 'chk_count',
						direction: 'desc'
					}
				],
				moreParams: {},
				perPage: 25,

			}
		},

		computed: {
			tableUrl() {
				return `/admin/closures/closed-accounts-pdc`;
			},
		},
	}
</script>