<template>
    <div class="row">

        <pulse-loader v-show="isLoading" :loading="loading" :color="color" :size="size" class="text-center"></pulse-loader>

        <div class="col-xs-12">

            <div v-if="success === true" class="alert alert-success dismissible">{{ message }}</div>
            <div v-else-if="success === false" class="alert alert-danger dismissible">{{ message }}</div>
            <div v-else-if="success === null" class="alert alert-warning dismissible">Processing, please wait...</div>
            <div v-else-if="initMessage" class="alert alert-success dismissible">{{ initMessage }}</div>


            <div v-show="nextPayment">
                <h3>Upcoming Invoice</h3>
                <table class="ui celled padded table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Charge</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ nextPayment.dateMdy }}</td>
                            <td v-if="nextPayment.amount">${{ nextPayment.amount.formatMoney() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div v-show="charges" class="m-t-40">
                <h3>Payment History</h3>
                <table class="ui celled padded table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="charge in charges">
                            <td>{{ charge.dateMdy }}</td>
                            <td v-if="charge.net_amount">${{ charge.net_amount.formatMoney() }}</td>
                            <td>{{ charge.status === 'succeeded' ? 'Paid' : 'Unpaid' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>

    </div>
</template>

<script>

import PulseLoader from 'vue-spinner/src/PulseLoader.vue'

export default {
    components: {
        PulseLoader
    },
    data() {
        return {
            isLoading: true,
            charges: {},
            nextPayment: {},
            success: 0
        }
    },
    mounted() {
        /**
         * Load the plans from Stripe
         */
        axios
            .get('/ajax/stripe/invoices')
            .then((response) => {
                this.isLoading = false
                this.charges = response.data.charges
                this.nextPayment = response.data.next_payment
            })
            .catch((response) => {
                this.isLoading = false
                this.errorMessage("Error: Stripe is not responding.")
            })
    }
}
</script>
