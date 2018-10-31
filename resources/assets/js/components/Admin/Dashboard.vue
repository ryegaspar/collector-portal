<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Today's Total's</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-right">Good For Today</th>
                                    <th class="text-right">Current Month</th>
                                    <th class="text-right">Next Month</th>
                                    <th class="text-right">Thirty Day</th>
                                    <th class="text-right">Ninety Day</th>
                                    <th class="text-right">All In</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in totals">
                                    <td>{{ item.UGP_DESC }}</td>
                                    <td class="text-right" v-text="toNumber(item.GFT)"></td>
                                    <td class="text-right" v-text="toNumber(item.CurrentMonth)"></td>
                                    <td class="text-right" v-text="toNumber(item.NextMonth)"></td>
                                    <td class="text-right" v-text="toNumber(item.30Day)"></td>
                                    <td class="text-right" v-text="toNumber(item.90Day)"></td>
                                    <td class="text-right" v-text="toNumber(item.AllIn)"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
     </div>
</template>

