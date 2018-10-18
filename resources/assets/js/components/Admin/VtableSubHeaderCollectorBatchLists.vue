<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group-append">
                    <div class="btn-group-sm">
                        <button type="button"
                                class="btn btn-danger mr-2"
                                @click="back()">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>

                        <button type="button"
                                class="btn btn-primary mr-2"
                                @click="exportExcel()">
                            <i class="fa fa-download"></i> Export Excel
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
		props: [
			'id'
        ],

		data() {
			return {
			}
		},

		methods: {
			back() {
				window.location.href = `/admin/collector-batches`;
            },

            exportExcel() {
				window.location.href = `/admin/collector-batches/${this.id}/list?export=excel`;
				// axios({
                //     url: `/admin/collector-batches/${this.id}/list?import=excel`,
                //     method: 'get',
                //     responseType: 'blob',
                // }).then((response) => {
                // 	const url = window.URL.createObjectUrl(new Blob([response.data]));
                // 	const link = document.createElement('a');
                // 	link.href = url;
                // 	link.setAttribute('download', 'file.xlsx');
                // 	document.body.appendChild(link);
                // 	link.click();
                // })
            }
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