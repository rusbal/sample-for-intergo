<template>
    <div class="row">

        <div class="col-xs-12">
            <div v-if="success === true" class="alert alert-success dismissible">{{ message }}</div>
            <div v-else-if="success === false" class="alert alert-danger dismissible">{{ message }}</div>
            <div v-else-if="success === null" class="alert alert-warning dismissible">Processing, please wait...</div>
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
                    <button @click="clicked('free')" type="button" class="btn form-control">{{ button('free') }}</button>
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
                    <button @click="clicked('silver')" type="button" class="btn form-control">{{ button('silver') }}</button>
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
                    <button @click="clicked('gold')" type="button" class="btn form-control">{{ button('gold') }}</button>
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
                    <button @click="clicked('platinum')" type="button" class="btn form-control">{{ button('platinum') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['plan'],
    data() {
        return {
            currentPlan: null,
            message: null,
            success: 0
        }
    },
    mounted() {
        this.currentPlan = this.plan
    },
    methods: {
        button(plan) {
            return this.currentPlan === plan ? '*** CURRENT PLAN ***' : 'SELECT'
        },
        clicked(plan) {
            if (this.currentPlan === plan) {
                return
            }

            if (! this.currentPlan) {
                this.createPlan(plan)
            } else {
                this.updatePlan(plan)
            }
        },
        createPlan(plan) {
            this.success = null
            this.message = null

            axios.post(
              Laravel.route('my.subscription@create'),
              { plan: plan }

            ).then((response) => {
                this.currentPlan = plan
                this.success = true
                this.message = `Your subscription plan was successfully set to "${plan.toUpperCase()}"`

            }).catch((response) => {
                this.success = false
                this.message = "Failure.  Please try again later."
            });
        },
        updatePlan(plan) {
            this.success = null
            this.message = null

            axios.patch(
              Laravel.route('my.subscription@update', 1), /* 1 is only a placeholder */
              { plan: plan }

            ).then((response) => {
                this.currentPlan = plan
                this.success = true
                this.message = `Your subscription plan was successfully set to "${plan.toUpperCase()}"`

            }).catch((response) => {
                this.success = false
                this.message = "Failure.  Please try again later."
            });
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
