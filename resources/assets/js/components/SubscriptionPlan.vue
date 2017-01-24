<template>
    <div class="row">

        <div class="col-xs-12">
            <div v-if="success === true" class="alert alert-success dismissible">{{ message }}</div>
            <div v-else-if="success === false" class="alert alert-danger dismissible">{{ message }}</div>
            <div v-else-if="success === null" class="alert alert-warning dismissible">Processing, please wait...</div>
            <div v-else-if="initMessage" class="alert alert-success dismissible">{{ initMessage }}</div>
        </div>

        <div class="col-xs-12 col-md-3">
            <div class="panel panel-free" :class="{ selected: isFree }">
                <div class="panel-heading">
                    <h3 class="panel-title">Free</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1> $0<span class="subscript">/mo</span></h1>
                    </div>
                    <table class="table">
                        <tr>
                            <td> Monitoring of one product </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <button @click="clicked('free', 0)" type="button" class="btn form-control">{{ button('free') }}</button>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-3">
            <div class="panel panel-silver" :class="{ selected: isSilver }">
                <div class="panel-heading">
                    <h3 class="panel-title">Silver</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1> $10<span class="subscript">/mo</span></h1>
                    </div>
                    <table class="table">
                        <tr>
                            <td> Monitoring of up to 10 product </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <button @click="clicked('silver', 10)" type="button" class="btn form-control">{{ button('silver') }}</button>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-3">
            <div class="panel panel-gold" :class="{ selected: isGold }">
                <div class="cnrflash">
                    <div class="cnrflash-inner">
                        <span class="cnrflash-label">MOST <br> POPULAR</span>
                    </div>
                </div>
                <div class="panel-heading">
                    <h3 class="panel-title">Gold</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1> $50<span class="subscript">/mo</span></h1>
                    </div>
                    <table class="table">
                        <tr>
                            <td> Monitoring of up to 50 product </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <button @click="clicked('gold', 50)" type="button" class="btn form-control">{{ button('gold') }}</button>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-3">
            <div class="panel panel-platinum" :class="{ selected: isPlatinum }">
                <div class="panel-heading">
                    <h3 class="panel-title">Platinum</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1> $100<span class="subscript">/mo</span></h1>
                    </div>
                    <table class="table">
                        <tr>
                            <td> Monitoring of unlimited number of products </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <button @click="clicked('platinum', 100)" type="button" class="btn form-control">{{ button('platinum') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['plan', 'initMessage'],
    data() {
        return {
            currentPlan: null,
            message: null,
            success: 0
        }
    },
    mounted() {
        this.currentPlan = this.plan ? this.plan : 'free'
    },
    methods: {
        button(plan) {
            return this.currentPlan === plan ? '*** CURRENT PLAN ***' : 'SELECT'
        },
        clicked(plan, amount) {
            if (this.currentPlan === plan) {
                return
            }

            if (! this.currentPlan) {
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

                // Logging
                if (! response.data.success) {
                    console.log(response.data.message);
                }

                if (response.data.redirect || ! response.data.success) {
                    window.location = '/my/subscription/create'

                } else {
                    this.currentPlan = plan
                    this.success = true
                    this.message = this.successMessage(plan)
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

                // Logging
                if (! response.data.success) {
                    console.log(response.data.message);
                }

                if (response.data.redirect || ! response.data.success) {
                    this.createPlan(plan, amount)

                } else {
                    this.currentPlan = plan
                    this.success = true
                    this.message = this.successMessage(plan)
                }

            }).catch((response) => {
                this.success = false
                this.message = "Failure.  Please try again later."
            });
        },
        successMessage(plan) {
            return `Your subscription plan was successfully set to "${plan.toUpperCase()}"`
        }
    },
    computed: {
        isFree()     { return this.currentPlan === 'free' },
        isSilver()   { return this.currentPlan === 'silver' },
        isGold()     { return this.currentPlan === 'gold' },
        isPlatinum() { return this.currentPlan === 'platinum' }
    }
}
</script>
