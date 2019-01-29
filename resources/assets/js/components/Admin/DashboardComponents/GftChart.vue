<script>
	import {Doughnut} from 'vue-chartjs';

	export default {
		extends: Doughnut,
        props: ['graphData'],
		mounted() {
			this.renderChart(this.graphData, {
				responsive: true,
                maintainAspectRatio: false,
                tooltips: {
					callbacks: {
						label: function (tooltipItem, data) {
							let groupName = data.labels[tooltipItem.index];
							let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
							let total = 0;
							let titles = ['Amount', 'Number of Payments', 'Average'];

							for (let i = 0; i < data.datasets[tooltipItem.datasetIndex].data.length; i++) {
								total += data.datasets[tooltipItem.datasetIndex].data[i];
							}

                            return [titles[tooltipItem.datasetIndex], groupName + ": " + value.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'), "Total :" + total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'),];
						}
					}
                }
			})
		}
	}
</script>