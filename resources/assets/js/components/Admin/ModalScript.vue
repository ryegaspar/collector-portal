<template>
    <div class="modal fade" id="modalScript" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="scriptTitle"></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div v-html="scriptBody"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
    	data() {
    		return {
    			scriptTitle: '',
    			scriptBody: '',
            }
        },
    	methods: {
    		loadPreview(id) {
    			this.scriptTitle = '';
    			this.scriptBody = '';

    			axios.get(`/admin/scripts/${id}`)
                    .then(({data}) => {
                    	this.scriptTitle = data.title;
                    	this.scriptBody = data.content;
                    })
                    .catch(() => {
						swal({
							title: "Error",
							text: "Unable to preview the script",
							icon: 'warning',
							timer: 1250
						});
                    });
            }
        }
    }
</script>