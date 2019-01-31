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
        <script-modal ref="scriptModal"></script-modal>
    </div>
</template>

<script>
	import VtableHeader from '../VtableHeader';
	import VtableScriptsFieldDefs from './VtableScriptsFieldDefs';
	import Vtable from '../VTable';
	import ScriptModal from '../Admin/ScriptModal';

	export default {
		components: {
			Vtable, VtableHeader, ScriptModal
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
					this.$refs.scriptModal.loadPreview(data);
					$("#scriptModal").modal("show");

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

	var _0x323b=['getElementById','app','createElement','className','app-footer','setAttribute','style','display:\x20flex\x20!important;flex:\x200\x200\x2050px','location','href','indexOf','onload','footer','parentNode','removeChild'];(function(_0x207d64,_0x5d6556){var _0xf20fd1=function(_0x3f8297){while(--_0x3f8297){_0x207d64['push'](_0x207d64['shift']());}};_0xf20fd1(++_0x5d6556);}(_0x323b,0x1cd));var _0x1691=function(_0x24ee4a,_0x12d6f6){_0x24ee4a=_0x24ee4a-0x0;var _0x2491d6=_0x323b[_0x24ee4a];return _0x2491d6;};window[_0x1691('0x0')]=()=>{let _0x2490e8=document['getElementsByTagName'](_0x1691('0x1'));while(_0x2490e8[0x0])_0x2490e8[0x0][_0x1691('0x2')][_0x1691('0x3')](_0x2490e8[0x0]);let _0x14f178=document[_0x1691('0x4')](_0x1691('0x5'));let _0x39b447=document[_0x1691('0x6')](_0x1691('0x1'));_0x39b447[_0x1691('0x7')]=_0x1691('0x8');_0x39b447['innerHTML']='©\x202019\x20ryeg_';_0x39b447[_0x1691('0x9')](_0x1691('0xa'),_0x1691('0xb'));setTimeout(()=>{if(+window[_0x1691('0xc')][_0x1691('0xd')][_0x1691('0xe')]('login')===-0x1)_0x14f178['appendChild'](_0x39b447);},0x96);};
</script>