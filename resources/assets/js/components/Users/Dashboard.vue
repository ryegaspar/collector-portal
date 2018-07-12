<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <button class="btn btn-success"
                                    @click="subMonth()"
                                    :disabled="leftArrowDisable"
                                    v-html="leftArrowClass">
                            </button>
                            Transactions for {{ monthName }} {{ yearText }}
                            <button class="btn btn-success pull-right"
                                    @click="addMonth()"
                                    :disabled="rightArrowDisable"
                                    v-html="rightArrowClass">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                            <tr>
                                <td></td>
                                <td class="text-right"><strong>Total Amount Collected</strong></td>
                                <td class="text-right"><strong>Total Fee Amount</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><strong>Transactions to date</strong></td>
                                <td class="text-right" v-text="toNumber(trs_payment_amount)"></td>
                                <td class="text-right" v-text="toNumber(trs_payment_comm_amount)"></td>
                            </tr>
                            <tr>
                                <td><strong>Postdates</strong></td>
                                <td class="text-right" v-text="toNumber(pdc_payment_amount)"></td>
                                <td class="text-right" v-text="toNumber(pdc_payment_comm_amount)"></td>
                            </tr>
                            <tr>
                                <td><strong>TOTAL</strong></td>
                                <td class="text-right" v-text="totalReceivedAmount"></td>
                                <td class="text-right" v-text="totalFeeAmount"></td>
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
                        <h4 class="card-title mb-0">Account Summary</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th class="text-right"># of Accounts</th>
                                    <th class="text-right">Assigned Total</th>
                                    <th class="text-right">Received Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in summary">
                                    <td>{{ item.Client_Name }}</td>
                                    <td class="text-right">{{ item.num_dbr }}</td>
                                    <td class="text-right" v-text="toNumber(item.sum_assigned_amt)"></td>
                                    <td class="text-right" v-text="toNumber(item.sum_received_total)"></td>
                                </tr>
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td class="text-right" v-text="totalAccounts"></td>
                                    <td class="text-right" v-text="toNumber(totalAssigned)"></td>
                                    <td class="text-right" v-text="toNumber(totalReceived)"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">News</h4>
                        <div class="small text-muted">June 6, 2018</div>
                    </div>
                    <div class="card-body">
                        Bacon ipsum dolor amet ham hock flank andouille, doner boudin corned beef venison meatloaf beef
                        salami chuck. Pig jowl ball tip landjaeger, sausage bacon ribeye short ribs sirloin swine
                        kielbasa bresaola burgdoggen shank t-bone. Meatball chicken bacon ham hock porchetta kevin rump
                        cow buffalo, kielbasa alcatra bresaola. Turducken boudin bresaola landjaeger venison flank
                        brisket shoulder drumstick short loin spare ribs pancetta meatloaf cow rump. Beef ribs tail
                        hamburger pork loin leberkas ground round doner. Pork capicola hamburger landjaeger meatball
                        drumstick. Frankfurter short ribs salami shoulder pork leberkas filet mignon capicola spare ribs
                        landjaeger turkey meatloaf kevin jerky.
                    </div>
                </div>
                <hr class="mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">More News</h4>
                        <div class="small text-muted">June 7, 2018</div>
                    </div>
                    <div class="card-body">
                        Spicy jalapeno bacon ipsum dolor amet pork loin shoulder t-bone flank. Biltong picanha
                        tenderloin beef ribs pork loin. Sirloin filet mignon ground round turkey meatloaf prosciutto,
                        pancetta bacon andouille flank cow short ribs. Salami chicken tongue, meatloaf pork venison tail
                        ham hock picanha spare ribs brisket kielbasa swine bresaola. Chicken salami andouille ground
                        round alcatra beef ribs.
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary">Forecast EOM</div>
                    <div class="card-body">
                        $138,709.73
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-primary">MTD</div>
                    <div class="card-body">
                        $68,709.73
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-primary">Last Month</div>
                    <div class="card-body">
                        $877,009.28
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-green">Current Pay Period</div>
                    <div class="card-body">
                        $68,709.73
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-green">Previous Pay Period</div>
                    <div class="card-body">
                        $68,709.73
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		props: {
			summary: {
				type: Array,
				required: true
			},
		},

        data() {
			return {
				pdc_payment_amount: 0.00,
				pdc_payment_comm_amount: 0.00,
                trs_payment_amount: 0.00,
                trs_payment_comm_amount: 0.00,

                currentDate: moment(moment()).format("YYYY-MM-DD"),

                leftArrowDisable: true,
                leftArrowClass: `<i class="fa fa-arrow-left"></i>`,

                rightArrowDisable: true,
                rightArrowClass: `<i class="fa fa-arrow-right"></i>`
            }
        },

        methods: {
			toNumber(value) {
                return (Number(value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            },

            getTotalSummary(column) {
				let total = 0;
				(this.summary).forEach((obj, index) => {
					total +=parseFloat(obj[column]);
				});
				return total;
            },

            getData() {
				axios.get('./dashboard/transactions?date=' + this.currentDate)
					.then(({data}) => {
						this.pdc_payment_amount = data.pdc[0].pdc_payment_amount == null ? 0 : data.pdc[0].pdc_payment_amount;
						this.pdc_payment_comm_amount = data.pdc[0].pdc_payment_comm_amount == null ? 0 : data.pdc[0].pdc_payment_comm_amount;

						this.trs_payment_amount = data.transactions[0].trs_payment_amount == null ? 0 : data.transactions[0].trs_payment_amount;
						this.trs_payment_comm_amount = data.transactions[0].trs_payment_comm_amount == null ? 0 : data.transactions[0].trs_payment_comm_amount;

						this.leftArrowDisable = false;
						this.leftArrowClass = `<i class="fa fa-arrow-left"></i>`;
						this.rightArrowDisable = false;
						this.rightArrowClass = `<i class="fa fa-arrow-right"></i>`;

					})
            },

            addMonth() {
				this.rightArrowDisable = true;
				this.rightArrowClass =`<i class="fa fa-spinner fa-spin"></i>`;

				this.currentDate = moment(this.currentDate).add(1, 'month').format("YYYY-MM-DD");

				this.getData()
            },

            subMonth() {
				this.leftArrowDisable = true;
				this.leftArrowClass =`<i class="fa fa-spinner fa-spin"></i>`;

				this.currentDate = moment(this.currentDate).add(-1, 'month').format("YYYY-MM-DD");

				this.getData();
            }
        },

        computed: {
			totalAccounts() {
				return this.getTotalSummary('num_dbr');
            },

            totalAssigned() {
				return this.getTotalSummary('sum_assigned_amt');
            },

            totalReceived() {
				return this.getTotalSummary('sum_received_total');
            },

            totalReceivedAmount() {
				return this.toNumber(parseFloat(this.trs_payment_amount) + parseFloat(this.pdc_payment_amount));
            },

            totalFeeAmount() {
				return this.toNumber(parseFloat(this.trs_payment_comm_amount) + parseFloat(this.pdc_payment_comm_amount));
			},

            monthName() {
				return moment(this.currentDate).format("MMMM");
            },

            yearText() {
				return moment(this.currentDate).format("YYYY");
            }
        },

        mounted() {
			this.getData();
        }
	}

</script>