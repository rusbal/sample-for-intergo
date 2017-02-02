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
                    <div class="field">
                        <label>First Name</label>
                        <input placeholder="First Name" type="text">
                    </div>
                    <div class="field">
                        <label>Last Name</label>
                        <input placeholder="Last Name" type="text">
                    </div>
                </div>
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
    methods: {
      onCancel (data) {
        console.log('my-detail-row: on-cancel')
        this.$events.fire('cancel-monitor-form', data)
      },
      onMonitor (data) {
        console.log('my-detail-row: on-monitor')
        this.$events.fire('submit-monitor-form', data)
      }
    },
  }
</script>