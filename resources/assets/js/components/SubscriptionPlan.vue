<style lang="sass" scoped>
    .btn-selected {
        color: green;
        font-weight: bold;
    }
    table.stats {
        margin: 20px auto;

        td:first-child {
            font-weight: bold;
        }
        td:nth-child(2) {
            padding-left: 20px;
            color: darkgreen;
            text-align: right;
        }
    }
</style>
<template>
    <div class="row">

        <pulse-loader v-show="arePlansLoading" :loading="loading" :color="color" :size="size" class="text-center"></pulse-loader>

        <div class="col-xs-12">
            <div v-if="success === true" class="alert alert-success dismissible">{{ message }}</div>
            <div v-else-if="success === false" class="alert alert-danger dismissible">{{ message }}</div>
            <div v-else-if="processing" class="alert alert-warning dismissible">Processing, please wait...</div>
            <div v-else-if="initMessage" class="alert alert-success dismissible">{{ initMessage }}</div>
        </div>

        <div v-for="(plan, planKey) in stripePlans" class="col-xs-12 col-md-3">
            <div class="panel" :class="panelClass(planKey)">
                <div v-show="planKey === mostPopular" class="cnrflash">
                    <div class="cnrflash-inner">
                        <span class="cnrflash-label">MOST <br> POPULAR</span>
                    </div>
                </div>
                <div class="panel-heading">
                    <h3 class="panel-title">{{ plan.name }}</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1> ${{ plan.amount }}<span class="subscript">/{{ plan.interval }}</span></h1>
                    </div>
                    <table class="table">
                        <tr>
                            <td v-html="window.nl2br(plan.description)"></td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">

                    <button v-if="currentPlan === planKey" @click="submit(planKey, plan.amount)" type="button" 
                        class="btn btn-block"
                        :class="buttonClass(planKey)">{{ button(planKey) }}</button>

                    <button v-else-if="isSubscribed()" @click="submit(planKey, plan.amount)" type="button" 
                        class="btn btn-block"
                        :class="buttonClass(planKey)">{{ button(planKey) }}</button>

                    <basic-checkout v-else :formProcessing="processing" :description="describe(plan)" :plan="planKey" :price="plan.amount" caption="Select"></basic-checkout>

                </div>

                <!-- Current Plan Stats -->
                <table v-show="userPlanStats.plan === planKey" class="stats">
                    <tr>
                        <td>Allocation:</td>
                        <td>
                            {{ userPlanStats.allocation >= 1000000 ? 'Unlimited' : userPlanStats.allocation }}
                        </td>
                    </tr>
                    <tr>
                        <td>Monitored:</td>
                        <td>{{ userPlanStats.monitorCount }}</td>
                    </tr>
                    <tr>
                        <td>Remaining:</td>
                        <td>
                            {{ userPlanStats.allocation >= 1000000 ? 'Unlimited' : userPlanStats.allocation - userPlanStats.monitorCount }}
                        </td>
                    </tr>
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
    props: ['plan', 'initMessage'],
    data() {
        return {
            window: window,
            mostPopular: 'gold',
            arePlansLoading: true,
            userPlanStats: {},
            stripePlans: [],
            message: null,
            success: 0,
            processing: false,
        }
    },
    computed: {
        currentPlan() {
            return this.userPlanStats.plan ? this.userPlanStats.plan : 'free'
        },
    },
    mounted() {
        this.userPlanStats = Laravel.userPlanStats

        /**
         * Load the plans from Stripe
         */
        axios
            .get('/ajax/stripe/plans')
            .then((response) => {
                this.arePlansLoading = false
                this.setStripePlans(response.data)
            })
            .catch((response) => {
                this.arePlansLoading = false
                this.errorMessage("Error: Stripe is not responding.")
            })
    },
    methods: {
        isSubscribed() {
            return Laravel.isSubscribed
        },
        setStripePlans(plans) {
            this.stripePlans = plans
        },
        describe(plan) {
            return `Monthly Subscription: ${plan.name.capitalize()}`
        },
        panelClass(plan) {
            let selected = plan === this.currentPlan ? ' selected' : ''
            return 'panel-' + plan + selected
        },
        buttonClass(plan) {
            return (this.currentPlan === plan ? 'btn-selected ' : 'btn-primary ')
                 + (this.processing ? 'disabled ' : '')
        },
        button(plan) {
            return this.currentPlan === plan ? '*** CURRENT PLAN ***' : 'SELECT'
        },
        submit(plan, amount) {
            if (this.currentPlan === plan) {
                return
            }

            this.updatePlan(plan, amount)
        },
        updatePlan(plan, amount) {
            this.success = null
            this.processing = true
            this.message = null

            axios.patch(
                Laravel.route('my.subscription@update', 1), /* 1 is only a placeholder */
                { plan: plan }

            ).then((response) => {
                this.processing = false
                if (response.data.success) {
                    this.currentPlan = plan

                    Laravel.userPlanStats = response.data.userPlanStats
                    this.userPlanStats = Laravel.userPlanStats

                    this.successMessage(`Your subscription plan was successfully set to "${plan.toUpperCase()}"`)
                } else {
                    this.errorMessage(response.data.message)
                }

            }).catch((response) => {
                this.processing = false
                this.errorMessage("Failure.  Please try again later.")
            });
        },
        successMessage(message) {
            this.success = true
            this.message = message
        },
        errorMessage(message) {
            this.success = false
            this.message = message
        },
    },
    events: {
        'subscription-new-success' (selectedPlanStats) {
            Laravel.isSubscribed = true
            Laravel.userPlanStats = selectedPlanStats
            this.userPlanStats = Laravel.userPlanStats
            this.successMessage(`Your subscription plan was successfully set to "${selectedPlanStats.plan.toUpperCase()}"`)
            this.$forceUpdate()
        },
        'subscription-error' (message) {
            this.errorMessage(message)
        },
        'set-processing-status' (bool) {
            this.processing = bool
            this.$forceUpdate()
        }
    }
}
</script>
