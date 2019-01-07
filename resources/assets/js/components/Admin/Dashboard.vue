<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Today's Totals</h4>
                        <object align="right"><a href="/admin/todays-totals" class="btn btn-info btn-md">Go to Detailed Totals</a></object>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <colgroup>
                                <col>
                                <col span="3" style="background-color:lightcyan;">
                                <col span="3" style="background-color:lavender;">
                                <col span="3" style="background-color:lightcyan;">
                                <col span="3" style="background-color:lavender;">
                                <col span="3" style="background-color:lightcyan;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>Collection Group</th>
                                <th class="text-right">GFT</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                <th class="text-right">Current Month</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                <!-- Next Month 
                                <th class="text-right">Next Month</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                -->
                                <th class="text-right">30 Day</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                                <th class="text-right">All</th>
                                <th class="text-left"># of pmts</th>
                                <th class="text-left">AVG pmt</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in summary">
                                    <td v-for="(index) in item">{{ toNumber(index) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Collection Hours</h4>
                        <object align="right"><a href="/admin/collector-hours" class="btn btn-info btn-md">Go to Detailed Hours</a></object>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                            <tr>
                                <th>Collection Group</th>
                                <th class="text-right">Number of Active Employees</th>
                                <th class="text-right">Total Hours Worked</th>
                                <th class="text-right">Current 30 Day</th>
                                <th class="text-right">Current Goal</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in collectionhours">
                                    <td v-for="(place, index) in item" :class="getClass(index)">{{ toNumber(place, index) }}</td>
                                    <td class="text-right" v-if="item.name.match(/Unifin.*/)">{{ (item.time*125).toFixed(2) }}</td>
                                    <td class="text-right" v-else>{{ (item.time*45).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		props: [
            'collectionhours',
            'summary'
        ], 

		methods: {
			toNumber(value, index = '') {
					return value;
			},

			getClass(index) {
				return (index !== 'name' ? 'text-right' : '');
			},

		},

        computed: {
        }
	}
</script>
