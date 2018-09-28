<template>
    <div class="modal fade" id="adminModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-if="isAdd">Add User</h4>
                    <h4 class="modal-title" v-else>Edit User</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="" @keydown="form.errors.clear()">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label>Username</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               :disabled="!isAdd"
                                               v-model="form.username">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('username')">
                                            {{ form.errors.get('username') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>First Name</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.first_name">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('first_name')">
                                            {{ form.errors.get('first_name') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Last Name</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               v-model="form.last_name">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('last_name')">
                                            {{ form.errors.get('last_name') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Email</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               :disabled="!isAdd"
                                               v-model="form.email">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('email')">
                                            {{ form.errors.get('email') }}
                                        </em>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label>Collect One ID</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               :disabled="!isAdd"
                                               v-model="form.tiger_user_id">
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('tiger_user_id')">
                                            {{ form.errors.get('tiger_user_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Role</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.access_level"
                                                @change="onAccessLevelChange">
                                            <option :value="role"
                                                    v-for="role in accessGroups">
                                                {{ role }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('access_level')">
                                            {{ form.errors.get('access_level') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group" v-if="showSite">
                                    <label>Site</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.site_id"
                                                @change="onSiteChange">
                                            <option :value="site.id"
                                                    v-for="site in sites">
                                                {{ site.name }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('site_id')">
                                            {{ form.errors.get('site_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group" v-if="showSubSite">
                                    <label>Sub Site</label>
                                    <div class="input-group">
                                        <select class="form-control"
                                                v-model="form.sub_site_id"
                                                @change="form.errors.clear()">
                                            <option :value="sub_site.id"
                                                    v-for="sub_site in sites[getIndex()].sub_site">
                                                {{ sub_site.name }}
                                            </option>
                                        </select>
                                        <em class="error invalid-feedback"
                                            v-if="form.errors.has('sub_site_id')">
                                            {{ form.errors.get('sub_site_id') }}
                                        </em>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button :class="this.isAdd ? 'btn btn-success' : 'btn btn-primary'"
                            @click="submit"
                            :disabled="isLoading || form.errors.any()"
                            v-html="persistButtonText">
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AdminOptionsStore from './AdminOptionsStore';

	export default {
		store: AdminOptionsStore,

		props: [
			'isAdd',
			'formData'
		],

		data() {
			return {
				persistButtonClass: 'btn btn-success',
				persistButtonText: 'Add',
				isLoading: false,

				form: new Form({
					username: '',
					last_name: '',
					first_name: '',
					tiger_user_id: '',
					email: '',
					access_level: '',
					site_id: '',
					sub_site_id: '',
				}),

				updateID: '',
			}
		},

		beforeCreate() {
			this.$store.dispatch('loadData');
		},

		methods: {
			submit() {
				let tempButtonText = this.persistButtonText;
				let action = 'post';
				let url = '/admin/admins';
				let notifyMessage = "Successfully added user";

				this.isLoading = true;
				this.persistButtonText = `<i class="fa fa-spinner fa-spin"></i>`

				if (!this.isAdd) {
					notifyMessage = "Successfully updated user";
					action = 'patch';
					url = `/admin/admins/${this.updateID}`;
				}

				this.form[action](url)
					.then(() => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;

						$("#adminModal").modal('hide');
						lib.swalSuccess(notifyMessage);
						this.$emit('submitted');
					})
					.catch((error) => {
						this.isLoading = false;
						this.persistButtonText = tempButtonText;
					})

			},

			resetModal() {
				this.form.reset();
			},

			populateData(data) {
				this.form.reset();
				_.assign(this.form, data);
				if (data.roles.length >= 1)
					this.form.access_level = data.roles[0].name;
				this.updateID = data.id;
			},

			getIndex() {
				return this.sites.findIndex(site => +site.id === +this.form.site_id);
			},

            onAccessLevelChange() {
				this.form.errors.clear();
                this.form.site_id = '';
            },

            onSiteChange() {
				this.form.errors.clear();
                this.form.sub_site_id = "";
            }
		},

		computed: {
			accessGroups() {
				return this.$store.state.accessGroups;
            },

            sites() {
				return this.$store.state.sites;
            },

			showSite() {
				return this.form.access_level === 'site-manager' || this.form.access_level === 'sub-site-manager' || this.form.access_level === 'team-leader';
			},

			showSubSite() {
				return this.form.site_id && (this.form.access_level === 'sub-site-manager' || this.form.access_level === 'team-leader');
			},
		},

		watch: {
			'isAdd': function (newVal, oldVal) {
				if (newVal) {
					this.persistButtonText = 'Add';
				}
				else {
					this.persistButtonText = 'Update';
				}
			},
		},
	}
</script>