<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-4">
                <div class="btn-group">
                    <button type="button" class="mb-1 btn btn-outline-purple dropdown-toggle"
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
                            <i v-if="field.visible === false" class="fa fa-eye-slash"></i>
                            {{ field.title }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <div class="btn-group input-group">
                    <input type="text"
                           class="form-control input-group-sm"
                           :placeholder="placeholder"
                           v-model="filterText"
                           @keyup="doFilter"
                           @keyup.enter="doFilter">
                    <span class="filter-clear fa fa-times-circle"
                          v-if="!!filterText"
                          @click="resetFilter"></span>
                    <span class="input-group-append">
                            <button class="btn btn-outline-success" @click="doFilter">Go</button>
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
			'fields',
			'placeholder'
		],

		data() {
			return {
				filterText: ''
			}
		},

		methods: {
			doToggleField(field) {
				this.field = !field;
				this.$events.fire('field-toggle', field);
			},

			doFilter: _.debounce(function() {
				this.$events.fire('filter-set', this.filterText)
			}, 500),

			resetFilter() {
				this.filterText = '';
				this.$events.fire('filter-reset');
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
        right: 55px;
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