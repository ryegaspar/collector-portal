<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group-append">
                    <div class="btn-group-sm">
                        <button type="button"
                                class="btn btn-primary mr-2"
                                @click="newBatch">
                            <i class="icon-plus"></i> New Batch
                        </button>
                    </div>
                    <!--<label class="col-form-label-sm mr-1">Status</label>-->
                    <!--<div class="dropdown">-->
                    <!--<button type="button"-->
                    <!--class="btn btn-outline-cyan btn-sm dropdown-toggle mr-1"-->
                    <!--data-toggle="dropdown"-->
                    <!--aria-haspopup="true"-->
                    <!--aria-expanded="false">-->
                    <!--<i class="fa fa-filter"></i> {{ filter2Text }} <span class="caret"></span>-->
                    <!--</button>-->
                    <!--<div class="dropdown-menu">-->
                    <!--&lt;!&ndash; list item&ndash;&gt;-->
                    <!--<a v-for="item in filter2Menus"-->
                    <!--class="dropdown-item"-->
                    <!--@click="filter2Run(item.code, item.text)"-->
                    <!--href="#">{{ item.text }}-->
                    <!--</a>-->
                    <!--</div>-->
                    <!--</div>-->
                </div>
            </div>
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		components: {
		},

		data() {
			return {
				// filter1Text: 'Group',
				// filter1Menus: [
				//     { code: "A", text: "All" },
				//     { code: "1", text: "SuperAdmin" },
				//     { code: "2", text: "Admin" }
				// ],
				// filter2Text: 'Status',
				// filter2Menus: [
				// 	{ code: "A", text: "All" },
				// 	{ code: "1", text: "Active" },
				// 	{ code: "0", text: "Inactive" },
				// ],
			}
		},

		methods: {
			newBatch() {
				this.$emit('newBatch');
            }

			// filter1Run(code, text) {
			// 	this.filter1Text = text;
			// 	this.$events.fire('filter1-change', code);
			// },

			// filter2Run(code, text) {
			// 	this.filter2Text = text;
			// 	this.$events.fire('filter2-change', code);
			// },
		},
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