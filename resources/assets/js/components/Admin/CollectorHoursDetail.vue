<template>
    <div class="animated fadeIn">
 
                    <h1 align="center">Detailed Collection Hours</h1>
                            <table id="TodaysTotals" 
                                data-toggle="table"
                                data-search="true"
                                data-filter-control="true" 
                                data-show-export="true"
                                class="table table-striped table-bordered"
                                style="width:100%">
                            <thead>
                            <tr>
                                <th data-field="collectiongroup" data-filter-control="select" data-sortable="true">Collection Group</th>
                                <th data-field="collectorname" data-filter-control="input" data-sortable="true">Employee Name</th>
                                <th data-field="teamleader" data-filter-control="input" data-sortable="true">Team Leader</th>
                                <th data-field="hoursworked" data-sortable="true">Total Hours Worked</th>
                                <th data-field="30day" data-sortable="true">Current 30 Day</th>
                                <th data-field="goal" data-sortable="true">Current Goal</th>
                            </tr>
                            </thead>
                            <tbody id="collectorHours">
                                <tr v-for="item in collectionhours">
                                    <td v-for="(place, index) in item" :class="getClass(index)">{{ toNumber(place, index) }}</td>
                                    <td class="text-right" v-if="item.name.match(/Unifin.*/) == Unifin ">{{ (item.time*37.5).toFixed(2) }}</td>
                                    <td class="text-right" v-else>{{ (item.time*125).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
    </div>
</template>

<script>
    $(document).ready(function(){
    $("#searchHoursInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#collectorHours tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
	export default {
		props: ['collectionhours'],

		methods: {
			toNumber(value, index = '') {
				if (index === 'name')
					return value;
                if (index === 'collector')
                    return value;
                if (index === 'teamleader')
					return value;
                if (Number.isInteger(value) === true)
                    var number = (Number(value)).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                else
                    var number = (Number(value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				return number;
			},
            calcGroupAverage(value, index = '') {
				if (index === 'name')
					return value;
                if (Number.isInteger(value) === true)
                    var number = (Number(value)).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                else
                    var number = (Number(value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				return number;
			},

			getClass(index) {
				return (index !== 'name' ? 'text-right' : '');
			},

			getTotalSummary(column) {
				let total = 0;
				(this.summary).forEach((obj, index) => {
					total +=parseFloat(obj[column]);
				});
				return total;
			},
		},
	}
</script>