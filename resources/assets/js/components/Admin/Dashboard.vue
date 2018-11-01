<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Summary</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                            <tr>
                                <th>Sub Site</th>
                                <th class="text-right">Today</th>
                                <th class="text-right">Current Month</th>
                                <th class="text-right">Next Month</th>
                                <th class="text-right">Next 30 Days</th>
                                <th class="text-right">Next 120 Days</th>
                                <th class="text-right">All</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in summary">
                                    <td v-for="(place, index) in item" :class="getClass(index)">{{ toNumber(place, index) }}</td>
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
		props: ['summary'],

		methods: {
			toNumber(value, index) {
				if (index === 'name')
					return value;

				return (Number(value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
			},

			getClass(index) {
				return (index !== 'name' ? 'text-right' : '');
			}
		}

	}
</script>
