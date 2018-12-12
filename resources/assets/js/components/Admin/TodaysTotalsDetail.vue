<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Detailed Today's Totals</h4>
                    </div>
                    <input class="form-control" id="searchTotalsInput" type="text" placeholder="Search...">
                    <div class="card-body">
                        <table class="table table-responsive-sm table-outlined table-hover">
                            <thead>
                            <tr>
                                <th>Collection Group</th>
                                <th>Employee Name</th>
                                <th>Total Hours Worked</th>
                                <th>Current Goal</th>
                            </tr>
                            </thead>
                            <tbody id="todaysTotals">
                                <tr v-for="item in todaystotals">
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
    $(document).ready(function(){
    $("#searchTotalsInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#todaysTotals tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
	export default {
		props: ['todaystotals'],

		methods: {
			toNumber(value, index = '') {
				if (index === 'name')
					return value;
                if (index === 'collector')
					return value;
                if (Number.isInteger(value) === true)
                    var number = (Number(value)).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                else
                    var number = (Number(value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				return value;
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