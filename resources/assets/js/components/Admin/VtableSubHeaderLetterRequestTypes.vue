<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group-append">
                    <div class="btn-group-sm">
                        <button type="button"
                                class="btn btn-primary mr-2"
                                @click="add">
                            <i class="icon-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		methods: {
			add() {
				this.$emit('addLetterRequestType');
			},
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