<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create a Script</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="form.title">
                                    <em class="error invalid-feedback"
                                        v-if="form.errors.has('title')">
                                        {{ form.errors.get('title') }}
                                    </em>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <tinymce id="content"
                                             class="mb-3"
                                             :value="form.content"
                                             v-model="form.content">
                                    </tinymce>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions pull-right">
                                    <button type="submit"
                                            class="btn btn-primary"
                                            @click.prevent="save"
                                            :disabled="isLoading"
                                            v-html="persistButtonText">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import Tinymce from "./TinymceVue"

	export default {
		components: {
			Tinymce
		},

		data() {
			return {
				persistButtonText: `<i class="fa fa-save"></i> Save`,
				isLoading: false,

				form: new Form({
					title: '',
					content: '',
				}),
			}
		},

	}
</script>