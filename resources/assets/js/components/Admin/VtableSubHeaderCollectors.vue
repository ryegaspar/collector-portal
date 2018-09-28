<template>
    <div class="filter-bar">
        <div class="form-inline">
            <!--<div class="toolbar">-->
            <div class="col-md-12 input-group" style="padding-left: 2px;padding-right: 2px">
                <div class="btn-group input-group-append">
                    <div class="btn-group-sm">
                        <button type="button"
                                class="btn btn-primary mr-2"
                                @click="addCollector">
                            <i class="icon-plus"></i> Add
                        </button>
                    </div>
                    <label class="col-form-label-sm mr-1">Active</label>
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
                    <label class="col-form-label-sm mr-1">Status</label>
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
                    <label class="col-form-label-sm mr-1">Sub Site</label>
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
                            <a href="#" class="dropdown-item" @click="filter3Run('A','All')">All</a>
                            <a href="#"
                               class="dropdown-item"
                               v-for="sub_site in sub_sites"
                               @click="filter3Run(sub_site.id, sub_site.name)">
                                {{ sub_site.name }}
                            </a>
                        </div>
                    </div>
                    <label class="col-form-label-sm mr-1">Team Leader</label>
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
                            <a href="#" class="dropdown-item" @click="filter4Run('A','All')">All</a>
                            <a href="#"
                               class="dropdown-item"
                               v-for="team_leader in team_leaders"
                               @click="filter4Run(team_leader.id, team_leader.full_name)">
                                {{ team_leader.full_name }}
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
	export default {
		components: {
		},

        // props: ['sub_sites'],

		data() {
			return {
				filter1Text: 'Active',
				filter1Menus: [
				    { code: "A", text: "All" },
				    { code: "1", text: "Active" },
				    { code: "0", text: "Inactive" }
				],

                filter2Text: 'Status',
                filter2Menus: [
                    { code: "A", text: "All" },
                    { code: 1, text: "Full Time" },
					{ code: 2, text: "Part Time" },
					{ code: 3, text: "Medical Leave" },
				],

                filter3Text: 'Sub Site',
                filter4Text: 'Team Leader',

                selectedSubSite: '',
                selectedTeamLeader: '',
			}
		},

		methods: {
			addCollector() {
				this.$emit('addCollector');
			},

			filter1Run(code, text) { //active
				this.filter1Text = text;
				this.$events.fire('filter1-change', code);
			},

			filter2Run(code, text) { //status
				this.filter2Text = text;
				this.$events.fire('filter2-change', code);
			},

            filter3Run(code, text) { //sub site
				this.filter3Text = text;
				this.selectedSubSite = code;

				Vue.nextTick(() => {
					this.$events.fire('filter3-change', code);
				});
			},

            filter4Run(code, text) { //team leader
				this.filter4Text = text;
				this.selectedTeamLeader = code;
				this.$events.fire('filter4-change', code);
            }
		},

        computed: {
			sub_sites() {
				return this.$store.state.sub_sites;
			},

            team_leaders() {
				if (this.selectedSubSite && this.selectedSubSite !== 'A') {
					return this.$store.state.team_leaders.filter((t) => {
						return +t.sub_site_id === +this.selectedSubSite;
                    })
                }

				return this.$store.state.team_leaders;
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