<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group-append">
                    <div class="btn-group-sm">
                        <button type="button"
                                class="btn btn-primary mr-2"
                                @click="addCorrespondenceLog">
                            <i class="icon-plus"></i> Add
                        </button>
                    </div>
                    <label class="col-form-label-sm mr-1">Status</label>
                    <div class="dropdown">
                        <button type="button"
                                class="btn btn-outline-cyan btn-sm dropdown-toggle mr-1"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <i class="fa fa-filter"></i> {{ filter1Text }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <!-- list item-->
                            <a v-for="item in filter1Menus"
                               class="dropdown-item"
                               @click="filter1Run(item.code, item.text)"
                               href="#">{{ item.text }}
                            </a>
                        </div>
                    </div>
                    <label class="col-form-label-sm mr-1">Origin</label>
                    <div class="dropdown">
                        <button type="button"
                                class="btn btn-outline-cyan btn-sm dropdown-toggle mr-1"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <i class="fa fa-filter"></i> {{ filter2Text }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <!-- list item-->
                            <a v-for="item in filter2Menus"
                               class="dropdown-item"
                               @click="filter2Run(item.code, item.text)"
                               href="#">{{ item.text }}
                            </a>
                        </div>
                    </div>
                    <label class="col-form-label-sm mr-1">Type</label>
                    <div class="dropdown">
                        <button type="button"
                                class="btn btn-outline-cyan btn-sm dropdown-toggle mr-1"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <i class="fa fa-filter"></i> {{ filter3Text }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <!-- list item-->
                            <a v-for="item in filter3Menus"
                               class="dropdown-item"
                               @click="filter3Run(item.code, item.text)"
                               href="#">{{ item.text }}
                            </a>
                        </div>
                    </div>
                    <label class="col-form-label-sm mr-1">Department</label>
                    <div class="dropdown">
                        <button type="button"
                                class="btn btn-outline-cyan btn-sm dropdown-toggle mr-1"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <i class="fa fa-filter"></i> {{ filter4Text }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <!-- list item-->
                            <a v-for="item in filter4Menus"
                               class="dropdown-item"
                               @click="filter4Run(item.code, item.text)"
                               href="#">{{ item.text }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 input-group" style="padding-left: 2px;padding-right: 2px">
            </div>
        </div>
    </div>
</template>

<script>
    import * as CorrespondenceLogOptions from '../../utilities/CorrespondenceLogOptions';
    
    export default {
		data() {
			return {
				filter1Text: 'Status',
				filter1Menus: [
					{ code: "0", text: "New" },
                    { code: "1", text: "In Progress" },
                    { code: "2", text: "Completed" },
                ],
                filter2Text: 'Origin',
				filter2Menus: [
					{ code: "0", text: "Client" },
                    { code: "1", text: "Consumer" },
                    { code: "2", text: "Third-Party" },
                ],
                filter3Text: 'Type',
				filter3Menus: [
					{ code: "0", text: "E-mail" },
                    { code: "1", text: "Voicemail" },
                    { code: "2", text: "Letter" },
                ],
                filter4Text: 'Department',
				filter4Menus: [
					{ code: "0", text: "Collections/Operations" },
                    { code: "1", text: "Compliance" },
                    { code: "2", text: "Client Relations" },
                    { code: "3", text: "Finance/Payment Processing" },
                    { code: "4", text: "Human Resources" },
                    { code: "5", text: "Technology" },
                ],
			}
		},

		methods: {
			addCorrespondenceLog() {
				this.$emit('addCorrespondenceLog');
			},

			filter1Run(code, text) { //active
				this.filter1Text = text;
				this.$events.fire('filter1-change', code);
            },
            
            filter2Run(code, text) { //active
				this.filter2Text = text;
				this.$events.fire('filter2-change', text);
            },
            
            filter3Run(code, text) { //active
				this.filter3Text = text;
				this.$events.fire('filter3-change', text);
            },
            
            filter4Run(code, text) { //active
				this.filter4Text = text;
				this.$events.fire('filter4-change', text);
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