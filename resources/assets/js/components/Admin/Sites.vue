<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <vtable-header :perPage=perPage
                                       :fields="fieldDefs"
                                       placeholder="id, name"></vtable-header>
                        <vtable-sub-header-sites @addSite="addSite"></vtable-sub-header-sites>
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
        <site-modal ref="siteModal"
                    :isAdd="isAdd"
                    @submitted="formSubmitted">
        </site-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableSitesFieldDefs from './VtableSitesFieldDefs';
	import Vtable from '../VTable';
    import VtableSubHeaderSites from './VtableSubHeaderSites';
    import SiteModal from './SiteModal'

	export default {

		components: {
			VtableHeader,
            VtableSubHeaderSites,
			Vtable,
			SiteModal,
		},

		data() {
			return {
				fieldDefs: VtableSitesFieldDefs,
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
			addSite() {
				this.isAdd = true;
				this.$refs.siteModal.resetModal();
				$("#siteModal").modal("show");
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

				this.$refs.siteModal.populateData(data);
				$("#siteModal").modal("show");

				button.removeAttribute("disabled");
				button.innerHTML = innerHTML;
			},
		},

		computed: {
			tableUrl() {
				return `/admin/sites`;
			},
		},
	}
</script>