<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="dbr #, client"></vtable-header>
                        <vtable-sub-header-sif-closures :prop-start-date="startText"
                                                        :prop-end-date="endText"
                                                        :append-params="moreParams"
                                                        :sort-order="sortOrder">
                        </vtable-sub-header-sif-closures>
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
	import VtableSubHeaderSifClosures from './VtableSubHeaderSifClosures';
	import Vtable from '../VTable';
	import VtableSifClosuresFieldDefs from "./VtableSifClosuresFieldDefs";

	export default {

		components: {
			VtableHeader,
            VtableSubHeaderSifClosures,
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

				moreParams: {
					'date': this.startDate() + '|' + this.endDate()
                },

				perPage: 25,

			}
		},

		methods: {

			startDate() {
				return moment(moment().subtract(14, 'days')).format("YYYY-MM-DD");
            },

            endDate() {
				return moment().format("YYYY-MM-DD");
            }
		},

		computed: {
			tableUrl() {
				return `/admin/closures/sif-closures`;
			},

            startText() {
				return moment().subtract(14, 'days');
            },

            endText() {
				return moment();
            }
		},
	}
</script>