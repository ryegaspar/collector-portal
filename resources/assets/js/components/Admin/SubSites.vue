<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="id, name"></vtable-header>
                        <vtable-sub-header-sub-sites @addSubSite="addSubSite"></vtable-sub-header-sub-sites>
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
                                            title="Edit"
                                            @click="itemAction('edit-item', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                </div>
                            </template>
                        </vtable>
                    </div>
                </div>
            </div>
        </div>
        <sub-site-modal ref="subSiteModal"
                        :isAdd="isAdd"
                        @submitted="formSubmitted">
        </sub-site-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableSubSitesFieldDefs from './VtableSubSitesFieldDefs';
	import VtableSubHeaderSubSites from './VtableSubHeadersSubSites';
	import SubSiteModal from './SubSiteModal';
	import Vtable from '../VTable';

	export default {

		components: {
			VtableHeader,
            VtableSubHeaderSubSites,
			Vtable,
			SubSiteModal,
		},

		data() {
			return {
				fieldDefs: VtableSubSitesFieldDefs,
				sortOrder: [
					{
						field: 'id',
						sortField: 'id',
						direction: 'asc'
					}
				],
				moreParams: {},
				perPage: 25,

				isAdd: true,
			}
		},

		methods: {
			addSubSite() {
				this.isAdd = true;
				this.$refs.subSiteModal.resetModal();
				$("#subSiteModal").modal("show");
			},

			formSubmitted() {
				this.$emit('reload');
			},

			itemAction(action, data, index, e) {
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

				this.isAdd = false;

				this.$refs.subSiteModal.populateData(data);
				$("#subSiteModal").modal("show");

				button.removeAttribute("disabled");
				button.innerHTML = innerHTML;
			},
		},

		computed: {
			tableUrl() {
				return `/admin/sub-sites`;
			},
		},
	}
</script>