<style lang="sass" scoped>
    .btn-selected {
        color: green;
        font-weight: bold;
    }
    table.stats {
        margin: 0 auto 20px;

        td:first-child {
            font-weight: bold;
        }
        td:nth-child(2) {
            padding-left: 20px;
            color: darkgreen;
        }
    }
</style>
<template>
    <div class="row">

        <pulse-loader v-show="arePlansLoading" :loading="loading" :color="color" :size="size" class="text-center"></pulse-loader>

        <div class="col-xs-12">
            <div v-if="success === true" class="alert alert-success dismissible">{{ message }}</div>
            <div v-else-if="success === false" class="alert alert-danger dismissible">{{ message }}</div>
            <div v-else-if="success === null" class="alert alert-warning dismissible">Processing, please wait...</div>
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
                    <button :class="buttonClass(planKey)" @click="submit(planKey, plan.amount)" type="button" class="btn form-control">
                        {{ button(planKey) }}
                    </button>
                </div>
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
            currentPlan: null,
            message: null,
            success: 0,
        }
    },
    mounted() {
        this.currentPlan = this.plan ? this.plan : 'free'
        this.userPlanStats = window.Laravel.userPlanStats

        /**
         * Load the plans from Stripe
         */
        axios
            .get('/ajax/stripe/plans')
            .then((response) => {
                this.arePlansLoading = false
                this.stripePlans = response.data
            })
            .catch((response) => {
                this.arePlansLoading = false
                this.errorMessage("Error: Stripe is not responding.")
            })
    },
    methods: {
        panelClass(plan) {
            let selected = plan === this.currentPlan ? ' selected' : ''
            return 'panel-' + plan + selected
        },
        buttonClass(plan) {
            let selected = plan === this.currentPlan ? '-selected' : ''
            let disabled = this.success === null ? ' disabled' : ''

            return 'btn' + selected + disabled
        },
        button(plan) {
            return this.currentPlan === plan ? '*** CURRENT PLAN ***' : 'SELECT'
        },
        submit(plan, amount) {
            if (this.currentPlan === plan) {
                return
            }

            if (Laravel.isNotSubscribed) {
                this.createPlan(plan, amount)
            } else {
                this.updatePlan(plan, amount)
            }
        },
        createPlan(plan, amount) {
            this.success = null
            this.message = null

            axios.post(
              Laravel.route('my.subscription@store'),
              { plan: plan, amount: amount }

            ).then((response) => {
                if (response.data.redirect) {
                    window.location = '/my/subscription/create'
                } else {
                    this.success = false
                    this.message = "Failure.  Please try again later."
                }

            }).catch((response) => {
                this.success = false
                this.message = "Failure.  Please try again later."
            });
        },
        updatePlan(plan, amount) {
            this.success = null
            this.message = null

            axios.patch(
                Laravel.route('my.subscription@update', 1), /* 1 is only a placeholder */
                { plan: plan }

            ).then((response) => {
                if (response.data.success) {
                    this.currentPlan = plan
                    this.userPlanStats = response.data.userPlanStats
                    this.successMessage(`Your subscription plan was successfully set to "${plan.toUpperCase()}"`)
                } else {
                    this.errorMessage(response.data.message)
                }

            }).catch((response) => {
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
    }
}
</script>
