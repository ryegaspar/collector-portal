<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12 input-group mb-3" style="padding-left: 2px;padding-right: 2px">
                            <div class="btn-group-sm">
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm dropdown-toggle mr-1"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    <i class="fa fa-user"></i> {{ roleText }} <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#"
                                       class="dropdown-item font-xs"
                                       v-for="role in accessGroups"
                                       @click="roleChange(role)"
                                       :value="role">{{ role }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="filter-bar form-inline">
                            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                                <label class="col-md-3"><strong>Adjustments</strong></label>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['read adjustment']">
                                    <label class="form-check-label">view</label>
                                </div>
                                <div class="btn-group input-group col-sm-4">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['update adjustment']">
                                    <label class="form-check-label">approve/deny</label>
                                </div>
                            </div>
                        </div>
                        <div class="filter-bar form-inline">
                            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                                <label class="col-md-3"><strong>Letter Request Types</strong></label>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['read letter-request-type']">
                                    <label class="form-check-label">view</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['create letter-request-type']">
                                    <label class="form-check-label">create</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['update letter-request-type']">
                                    <label class="form-check-label">edit</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['disable letter-request-type']">
                                    <label class="form-check-label">disable</label>
                                </div>
                            </div>
                        </div>
                        <div class="filter-bar form-inline">
                            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                                <label class="col-md-3"><strong>Scripts</strong></label>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['read script']">
                                    <label class="form-check-label">view</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['create script']">
                                    <label class="form-check-label">create</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['update script']">
                                    <label class="form-check-label">edit</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['delete script']">
                                    <label class="form-check-label">delete</label>
                                </div>
                            </div>
                        </div>
                        <div class="filter-bar form-inline">
                            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                                <label class="col-md-3"><strong>Collector</strong></label>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['read collector']">
                                    <label class="form-check-label">view</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['create collector']">
                                    <label class="form-check-label">create</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['update collector']">
                                    <label class="form-check-label">edit</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['disable collector']">
                                    <label class="form-check-label">disable</label>
                                </div>
                            </div>
                        </div>
                        <div class="filter-bar form-inline">
                            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                                <label class="col-md-3"><strong>Collector Batches</strong></label>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['read collector-batch']">
                                    <label class="form-check-label">view</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['create collector-batch']">
                                    <label class="form-check-label">create</label>
                                </div>
                                <div class="btn-group input-group col-sm-2">
                                    <input type="checkbox" class="form-check-input"
                                           v-model="permissions['delete collector-batch']">
                                    <label class="form-check-label">delete</label>
                                </div>
                            </div>
                        </div>
                        <!--<div class="filter-bar form-inline">-->
                            <!--<div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">-->
                                <!--<label class="col-md-2"><strong>Users</strong></label>-->
                                <!--<div class="btn-group input-group col-sm-2">-->
                                    <!--<input type="checkbox" class="form-check-input"-->
                                           <!--v-model="permissions['read users']">-->
                                    <!--<label class="form-check-label">view users</label>-->
                                <!--</div>-->
                                <!--<div class="btn-group input-group col-sm-2">-->
                                    <!--<input type="checkbox" class="form-check-input"-->
                                           <!--v-model="permissions['create users']">-->
                                    <!--<label class="form-check-label">create users</label>-->
                                <!--</div>-->
                                <!--<div class="btn-group input-group col-sm-2">-->
                                    <!--<input type="checkbox" class="form-check-input"-->
                                           <!--v-model="permissions['update users']">-->
                                    <!--<label class="form-check-label">edit users</label>-->
                                <!--</div>-->
                                <!--<div class="btn-group input-group col-sm-2">-->
                                    <!--<input type="checkbox" class="form-check-input"-->
                                           <!--v-model="permissions['disable users']">-->
                                    <!--<label class="form-check-label">disable users</label>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions pull-right">
                                    <button type="submit"
                                            class="btn btn-primary"
                                            @click.prevent="updatePermissions"
                                            :disabled="isLoading"
                                            v-html="updateText">
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
	export default {
		data() {
			return {
				updateText: 'Update',
				isLoading: false,

				roleText: 'Role',
				accessGroups: [],
				permissions: {
					// 'read users': false,
					// 'create users': false,
					// 'update users': false,
					// 'disable users': false,

					'read adjustment': false,
					'update adjustment': false,

					'read script': false,
					'create script': false,
					'update script': false,
					'delete script': false,
				},
			}
		},

		methods: {
			roleChange(role) {
				this.roleText = role;

				axios.get(`/admin/roles-permissions/${role}`)
					.then(({data}) => {
						this.resetPermissions();

						data.forEach((element) => {
							this.permissions[element] = true;
						});
					})
					.catch((error) => {

					});
			},

			resetPermissions() {
				Object.keys(this.permissions).map((key) => {
					this.permissions[key] = false;
				});
			},

			updatePermissions() {
				if (this.roleText === 'Role') {
					return;
				}

				let tempButtonText = this.updateText;
				this.updateText = `<i class="fa fa-spinner fa-spin"></i>`

				axios.patch(`/admin/roles-permissions/${this.roleText}`, {permissions: this.permissions})
					.then(() => {

						lib.swalSuccess(`Successfully updated permissions for ${this.roleText}`);

						this.isLoading = false;
						this.updateText = tempButtonText;
					})
					.catch((error) => {
						lib.swalError(error.message);

						this.isLoading = false;
						this.updateText = tempButtonText;
					});
			}
		},

		created() {
			axios.get('/admin/roles')
				.then(({data}) => {
					data.forEach((data) => {
						if (data !== 'super-admin')
							this.accessGroups.push(data);
					});
				})
				.catch((error) => {
					console.log(error);
				});
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