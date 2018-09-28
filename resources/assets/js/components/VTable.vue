<script>
	import Vuetable from 'vuetable-2'
	import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
	import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

	export default {
		name: 'template-vuetable',

		components: {
			Vuetable,
			VuetablePagination,
			VuetablePaginationInfo
		},

		props: {
			apiUrl: {
				type: String,
				required: true
			},
			fields: {
				type: Array,
				required: true
			},
			sortOrder: {
				type: Array,
				default() {
					return []
				}
			},
			appendParams: {
				type: Object,
				default() {
					return {}
				}
			},
			perPage: {
				type: Number,
				default() {
					return 15;
				}
			}
		},

		data() {
			return {
				css: {
					table: {
						tableClass: 'table table-paper table-condensed table-bordered',
						ascendingIcon: 'fa fa-chevron-up',
						descendingIcon: 'fa fa-chevron-down'
					},

					pagination: {
						wrapperClass: 'pagination',
						activeClass: 'active',
						disabledClass: 'disabled',
						pageClass: 'page',
						linkClass: 'link',

						icons: {
							first: 'fa fa-angle-double-left',
							prev: 'fa fa-angle-left',
							next: 'fa fa-angle-right',
							last: 'fa fa-angle-double-right',
						}
					},
				},
				perPageShow: this.perPage
			}
		},

		mounted() {
			this.$events.$on('search-set', eventData => this.onSearchSet(eventData));
			this.$events.$on('search-reset', eventData => this.onSearchReset());
			this.$events.$on('change-per-page', eventData => this.onChangePagesToShow(eventData));
			this.$events.$on('date-change', (date1, date2) => this.onPaydateChange(date1, date2));
			this.$events.$on('status-change', eventData => this.onStatusChange(eventData));

			this.$events.$on('filter1-change', eventData => this.onFilter1Change(eventData));
			this.$events.$on('filter2-change', eventData => this.onFilter2Change(eventData));
			this.$events.$on('filter3-change', eventData => this.onFilter3Change(eventData));
			this.$events.$on('filter4-change', eventData => this.onFilter4Change(eventData));
		},

		render(h) {
			return h(
				'div',
				{
					class: {
						vtable: true,
					}
				},
				[
					this.renderVuetable(h),
					this.renderPagination(h)
				]
			)
		},

		methods: {
			renderVuetable(h) {
				return h(
					'vuetable',
					{
						ref: 'vuetable',
						// domProps: {
						// fields: this.fields
						// },
						props: {
							apiUrl: this.apiUrl,
							fields: this.fields,
							css: this.css.table,
							sortOrder: this.sortOrder,
							appendParams: this.appendParams,
							paginationPath: '',
							perPage: this.perPageShow,
						},
						on: {
							'vuetable:pagination-data': this.onPaginationData
						},
						scopedSlots: this.$vnode.data.scopedSlots
					},
				)
			},

			renderPagination(h) {
				return h(
					'div',
					{
						class: {
							'vuetable-pagination': true
						},
						style: {
							'padding-top': '10px'
						}
					},
					[
						h(
							'div',
							{
								class: {
									'row': true
								}
							},
							[
								h(
									'div',
									{
										class: {
											'col-md-6': true,
										}
									},
									[
										h('vuetable-pagination-info', {
											ref: 'paginationInfo',
											infoClass: 'pagination-info',
										}),
									]
								),

								h(
									'div',
									{
										class: {
											'col-md-6': true,
										}
									},
									[
										h('vuetable-pagination', {
											ref: 'pagination',
											props: {
												css: this.css.pagination
											},
											on: {
												'vuetable-pagination:change-page': this.onChangePage
											}
										})
									]
								)
							]
						),
					]
				)
			},

			onSearchSet(searchText) {
				this.appendParams.search = searchText;
				Vue.nextTick(() => this.$refs.vuetable.refresh());
			},

			onSearchReset() {
				delete this.appendParams.search;
				Vue.nextTick(() => this.$refs.vuetable.refresh());
			},

			onPaginationData(paginationData) {
				this.$refs.pagination.setPaginationData(paginationData);
				this.$refs.paginationInfo.setPaginationData(paginationData);
			},

			onChangePage(page) {
				this.$refs.vuetable.changePage(page)
			},

			onChangePagesToShow(page) {
				this.perPageShow = page;
			},

			onPaydateChange(date1, date2) {
				this.appendParams.date = date1 + '|' + date2;
				Vue.nextTick(() => this.$refs.vuetable.refresh());
			},

            onFilter1Change(filter) {
				this.appendParams.filter1 = filter;
				Vue.nextTick(() => this.$refs.vuetable.refresh());
            },

            onFilter2Change(filter) {
				this.appendParams.filter2 = filter;
				Vue.nextTick(() => this.$refs.vuetable.refresh());
            },

            onFilter3Change(filter) {
				this.appendParams.filter3 = filter;
				Vue.nextTick(() => this.$refs.vuetable.refresh());
            },

            onFilter4Change(filter) {
				this.appendParams.filter4 = filter;
				Vue.nextTick(() => this.$refs.vuetable.refresh());
            }
		},

		created() {
			this.$parent.$on('reload', () => {
				this.$refs.vuetable.reload();
			})
		},

		events: {
			'field-toggle'(field) {
				field.visible = !field.visible;
				this.$refs.vuetable.normalizeFields();
			}
		},

		watch: {
			perPageShow() {
				this.$nextTick(() => {
					this.$refs.vuetable.refresh();
				})
			}
		}
	}
</script>

<style>
    .vtable {
        padding-bottom: 20px;
    }

    .pagination {
        margin: 0;
        float: right;
    }

    .pagination a {
        text-decoration: none;
        cursor: pointer;
    }

    .pagination a.page {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }

    .pagination a.page.active {
        color: white;
        background-color: #337ab7;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }

    .pagination a.btn-nav {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
    }

    .pagination a.btn-nav.disabled {
        color: lightgray;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
        cursor: not-allowed;
    }

    .pagination-info {
        float: left;
    }

    th.sortable:hover {
        text-decoration: none !important;
    }
</style>