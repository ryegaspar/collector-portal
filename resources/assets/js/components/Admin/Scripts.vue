<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!--<vtable-header :perPage=perPage-->
                                       <!--:fields="fieldDefs"-->
                                       <!--placeholder="id, username, name"></vtable-header>-->
                        <!--<vtable-sub-header-users @addUser="addUser">-->
                        <!--</vtable-sub-header-users>-->
                        <!--<vtable :api-url="tableUrl"-->
                                <!--:fields="fieldDefs"-->
                                <!--:sort-order="sortOrder"-->
                                <!--:append-params="moreParams"-->
                                <!--:perPage=perPage>-->
                            <!--<template slot="actions" slot-scope="props">-->
                                <!--<div class="custom-actions">-->
                                    <!--<button type="button"-->
                                            <!--class="btn btn-sm btn-info"-->
                                            <!--data-toggle="tooltip"-->
                                            <!--data-placement="top"-->
                                            <!--title="Edit"-->
                                            <!--v-if="isNotCurrentUser(props.rowData.id)"-->
                                            <!--@click="itemAction('edit-item', props.rowData, props.rowIndex, $event)">-->
                                        <!--<i class="fa fa-pencil-square-o"></i>-->
                                    <!--</button>-->
                                    <!--<button type="button"-->
                                            <!--class="btn btn-sm"-->
                                            <!--:class="props.rowData.active ? 'btn-danger' : 'btn-success'"-->
                                            <!--data-toggle="tooltip"-->
                                            <!--data-placement="top"-->
                                            <!--:title="props.rowData.active ? 'Deactivate' : 'Activate'"-->
                                            <!--v-if="isNotCurrentUser(props.rowData.id)"-->
                                            <!--@click="itemAction('toggle-active', props.rowData, props.rowIndex, $event)">-->
                                        <!--<i :class="props.rowData.active ? 'fa fa-thumbs-down' : 'fa fa-thumbs-up'"></i>-->
                                    <!--</button>-->
                                <!--</div>-->
                            <!--</template>-->
                        <!--</vtable>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>