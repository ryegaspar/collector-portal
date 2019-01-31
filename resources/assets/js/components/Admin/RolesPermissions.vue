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
                        <div class="filter-bar form-inline" v-for="permissionList in permissionLists">
                            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                                <label class="col-md-3"><strong>{{ permissionList.description }}</strong></label>
                                <div class="btn-group input-group col-sm-2"
                                     v-for="permissionObject in permissionList.perms">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           v-model="permissions[permissionObject.permission_description]">
                                    <label class="form-check-label">{{ permissionObject.name }}</label>
                                </div>
                            </div>
                        </div>
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
				permissions: {},

				permissionLists: permissionsJson
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
			}
			,

			resetPermissions() {
				Object.keys(this.permissions).map((key) => {
					this.permissions[key] = false;
				});
			}
			,

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
		}
		,

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

	const permissionsJson = [
		{
			description: 'Adjustments',
			perms: [
				{
					name: 'view',
					permission_description: 'read adjustment',
				},
				{
					name: 'approve/deny',
					permission_description: 'update adjustment',
				}
			]
		},
		{
			description: 'Calendars',
			perms: [
				{
					name: 'view',
					permission_description: 'read calendar',
				}
			]
		},
		{
			description: 'Closure Reports',
			perms: [
				{
					name: 'view',
					permission_description: 'view closure-report'
				}
			]
		},
		{
			description: 'Collector',
			perms: [
				{
					name: 'view',
					permission_description: 'read collector',
				},
				{
					name: 'create',
					permission_description: 'create collector',
				},
				{
					name: 'edit',
					permission_description: 'update collector',
				},
				{
					name: 'disable',
					permission_description: 'disable collector'
				}
			]
		},
		{
			description: 'Collector Batches',
			perms: [
				{
					name: 'view',
					permission_description: 'read collector-batch',
				},
				{
					name: 'create',
					permission_description: 'create collector-batch',
				},
				{
					name: 'delete',
					permission_description: 'delete collector-batch'
				}
			]
		},
		{
			description: 'Desk Transfer Requests',
			perms: [
				{
					name: 'review',
					permission_description: 'review desk-transfer-request',
				}
			]
		},
		{
			description: 'Letter Request Types',
			perms: [
				{
					name: 'view',
					permission_description: 'read letter-request-type',
				},
				{
					name: 'create',
					permission_description: 'create letter-request-type',
				},
				{
					name: 'edit',
					permission_description: 'update letter-request-type',
				},
				{
					name: 'disable',
					permission_description: 'disable letter-request-type',
				}
			]
		},
		{
			description: 'Scripts',
			perms: [
				{
					name: 'view',
					permission_description: 'read script',
				},
				{
					name: 'create',
					permission_description: 'create script',
				},
				{
					name: 'edit',
					permission_description: 'update script',
				},
				{
					name: 'delete',
					permission_description: 'delete script'
				}
			]
		}
	];
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