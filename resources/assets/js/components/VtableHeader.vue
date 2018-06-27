<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-4 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group-sm">
                    <button type="button"
                            class="btn btn-outline-primary btn-sm dropdown-toggle mr-1"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="icon-layers"></i> Per Page <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="#"
                           class="dropdown-item font-xs"
                           :class="page == perPageShow ? 'disabled' : ''"
                           v-for="page in pages"
                           @click="changePerPage(page)"> {{ page }} </a>
                    </div>
                </div>
                <div class="btn-group input-group-append">
                    <button type="button"
                            class="btn btn-outline-purple btn-sm dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="fa fa-list-alt"></i> Toggle Columns <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu">
                        <!-- list item-->
                        <a class="dropdown-item"
                           v-for="field in fields"
                           @click.prevent="doToggleField(field)"
                           href="#">
                            <i v-if="field.visible === true || field.visible === undefined || field.visible === 'undefined'"
                               class="fa fa-eye"></i>
                            <i v-if="field.visible === false"
                               class="fa fa-eye-slash"></i>
                            {{ field.title }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group btn-group-sm">
                    <input type="text"
                           class="form-control input-group-sm"
                           :placeholder="placeholder"
                           v-model="searchText"
                           @keyup="doSearch"
                           @keyup.enter="doSearch">
                    <span class="filter-clear fa fa-times-circle"
                          v-if="!!searchText"
                          @click="resetSearch"></span>
                    <span class="input-group-append">
                            <button class="btn btn-outline-success btn-sm" @click="doSearch">Go</button>
                        </span>
                </div>
            </div>
            <!--</div>-->
        </div>
    </div>
</template>

<script>
	export default {
		props: [
			'perPage',
			'fields',
			'placeholder'
		],

		data() {
			return {
				searchText: '',
				pages: [5, 10, 25, 50, 100],
				perPageShow: this.perPage
			}
		},

		methods: {
			changePerPage(page) {
				if (page !== this.perPageShow) {
					this.$events.fire('change-per-page', page);
					this.perPageShow = page;
				}
			},

			doToggleField(field) {
				this.field = !field;
				this.$events.fire('field-toggle', field);
			},

			doSearch: _.debounce(function () {
				this.$events.fire('search-set', this.searchText)
			}, 500),

			resetSearch() {
				this.searchText = '';
				this.$events.fire('search-reset');
			}
		},

		computed: {
			hasInput() {
				return !!this.filterText;
			}
		}
	}
</script>

<style>
    .filter-type {
        padding-top: 7px;
        font-size: 15px;
        font-weight: bold;
    }

    .filter-clear {
        z-index: 10;
        position: absolute;
        right: 40px;
        top: 0;
        bottom: 0;
        height: 14px;
        margin: auto;
        font-size: 14px;
        cursor: pointer;
    }

    .filter-bar {
        margin-top: 3px;
        margin-bottom: 5px;
        background: #fcfcfc;
        padding: 5px;
        border: 1px solid #e7e6e8;
    }
</style>