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
                                            title="Show"
                                            @click="itemAction('show', props.rowData, props.rowIndex, $event)">
                                        <i class="fa fa-search"></i>
                                    </button>
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
	import ModalScript from '../Admin/ModalScript';

	export default {
		components: {
			Vtable, VtableHeader, ModalScript
		},

		data() {
			return {
				fieldDefs: VtableScriptsFieldDefs,
				sortOrder: [
					{
						field: 'title',
						sortField: 'title',
						direction: 'asc'
					}
				],
				moreParams: {},
				perPage: 25,
			}
		},

		methods: {
			itemAction(action, data, index, e) {
				let innerHTML = e.currentTarget.innerHTML;
				let button = e.currentTarget;

				$('[data-toggle="tooltip"]').tooltip('hide');

				button.setAttribute("disabled", true);
				button.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;

				if (action === 'show') {
					this.$refs.modalScript.loadPreview(`/scripts/${data.id}`);
					$("#modalScript").modal("show");

					button.removeAttribute("disabled");
					button.innerHTML = innerHTML;

					return;
				}
			},
		},

		computed: {
			tableUrl() {
				return `/scripts`;
			},
		},

	}
</script>