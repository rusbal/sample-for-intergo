<template>
    <div>
        <div class="text-right">
            <button class="btn btn-primary btn-block"
                @click="openStripe"
                :class="{ 'btn-loading': isProcessing }"
                :disabled="isProcessing"
            >
               {{ caption }} 
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'plan',
            'price',
            'caption',
            'disabled',
            'formProcessing',
        ],
        data() {
            return {
                quantity: 1,
                stripeHandler: null,
                processing: false,
                cancelled: true,
            }
        },
        computed: {
            isProcessing() {
                return this.formProcessing || this.processing
            },
            disabledClass() {
                return this.disabled ? 'disabled' : ''
            },
            totalPrice() {
                return this.price * 100
            }
        },
        methods: {
            initStripe() {
                const handler = StripeCheckout.configure({
                    key: Laravel.stripePublicKey
                })

                window.addEventListener('popstate', () => {
                    handler.close()
                })

                return handler
            },
            openStripe(callback) {
                const vm = this
                this.setProcessing(true)

                this.stripeHandler.open({
                    name: Laravel.app.name,
                    description: this.description,
                    currency: "usd",
                    allowRememberMe: false,
                    panelLabel: 'Pay {{amount}}',
                    amount: this.totalPrice,
                    image: '/img/skubright-square-logo.png',
                    token: this.purchaseSubscription,
                    closed: function() {
                        if (vm.cancelled) {
                            vm.setProcessing(false)
                        }
                    }
                })
            },
            purchaseSubscription(token) {
                this.cancelled = false;

                axios.post('/my/subscription/save-new-subscription', {
                    plan: this.plan,
                    payment_token: token.id,

                }).then(response => {
                    this.setProcessing(false)
                    console.log("New subscription succeeded")
                    this.$events.fire('subscription-new-success', response.data.userPlanStats)

                }).catch(response => {
                    this.setProcessing(false)
                    this.$events.fire('subscription-error', 'Payment succeeded but there was a failure of subscription.  Please contact us.')
                })
            },
            setProcessing(bool) {
                this.processing = bool
                this.$events.fire('set-processing-status', bool)
            }
        },
        created() {
            this.stripeHandler = this.initStripe()
        }
    }
</script>
