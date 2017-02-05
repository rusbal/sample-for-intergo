<template>
    <div>
        <!--
        <div class="inline field"> <label>Product ID: </label> <span>{{rowData.product_id}}</span> </div>
        <div class="inline field"> <label>Fulfillment Channel: </label> <span>{{rowData.fulfillment_channel}}</span> </div>
        <div class="inline field"> <label>Warehouse Condition: </label> <span>{{rowData.warehouse_condition_code}}</span> </div>
        <div class="inline field"> <label>Condition Type: </label> <span>{{rowData.condition_type}}</span> </div>
        <div class="inline field"> <label>Price: </label> <span>{{rowData.price}}</span> </div>
        -->
        <div class="ui inverted segment">
            <div class="ui inverted form">
                <div class="two fields">
                    <div class="field" :class="{ error: mapInvalid }">
                        <label>Minimum Advertized Price </label>
                        <input v-model="minimumAdvertizedPrice" ref="minimumAdvertizedPrice" placeholder="Minimum Advertized Price" type="text">
                    </div>
                    <div class="field" :class="{ error: moqInvalid }">
                        <label>Maximum Number of Sellers</label>
                        <input v-model="maximumOfferQuantity" placeholder="Maximum Number of Sellers" type="text">
                    </div>
                </div>
                <p v-show="withError">Please enter your monitoring setup.</p>
                <div class="field row">
                    <button class="ui right floated primary button" @click="onMonitor(rowData)"> Monitor </button>
                    <button class="ui right floated button" @click="onCancel(rowData)"> Cancel </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    data() {
        return {
            minimumAdvertizedPrice: '',
            maximumOfferQuantity: '',
            mapInvalid: false,
            moqInvalid: false
        }
    },
    mounted() {
        if (this.rowData.minimum_advertized_price !== null) {
            this.minimumAdvertizedPrice = this.rowData.minimum_advertized_price.toString()
        }
        if (this.rowData.maximum_offer_quantity !== null) {
            this.maximumOfferQuantity   = this.rowData.maximum_offer_quantity.toString()
        }
        this.$refs.minimumAdvertizedPrice.focus()
    },
    computed: {
        withError() {
            return this.mapInvalid && this.moqInvalid
        }
    },
    methods: {
      onCancel(data) {
        this.$events.fire('cancel-monitor-form', data)
      },
      trim(string) {
         /**
          * Trim polyfill
          * https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/String/trim
          */
         return string.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '')
      },
      onMonitor(data) {
        this.minimumAdvertizedPrice == this.trim(this.minimumAdvertizedPrice.toString())
        this.maximumOfferQuantity == this.trim(this.maximumOfferQuantity.toString())

        /**
         * Validation
         */
        if (this.minimumAdvertizedPrice === '') {
           this.mapInvalid = true
        } else {
           this.mapInvalid = isNaN(this.minimumAdvertizedPrice)
        }

        if (this.maximumOfferQuantity === '') {
           this.moqInvalid = true
        } else {
           this.moqInvalid = isNaN(this.maximumOfferQuantity)
        }

        if (this.mapInvalid && this.moqInvalid) {
           /**
            * Invalid input
            */
           this.$refs.minimumAdvertizedPrice.focus()
           return
        }

        // Assign validated input
        data.minimumAdvertizedPrice = this.mapInvalid ? null : this.minimumAdvertizedPrice
        data.maximumOfferQuantity   = this.moqInvalid ? null : this.maximumOfferQuantity

        this.$events.fire('submit-monitor-form', data)
      }
    }
  }
</script>