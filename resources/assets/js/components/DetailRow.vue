<template>
    <div @click="onClick">
        <div class="inline field">
            <label>Product ID: </label>
            <span>{{rowData.product_id}}</span>
        </div>
        <div class="inline field">
            <label>Fulfillment Channel: </label>
            <span>{{rowData.fulfillment_channel}}</span>
        </div>
        <div class="inline field">
            <label>Warehouse Condition: </label>
            <span>{{rowData.warehouse_condition_code}}</span>
        </div>
        <div class="inline field">
            <label>Condition Type: </label>
            <span>{{rowData.condition_type}}</span>
        </div>
        <div class="inline field">
            <label>Price: </label>
            <span>{{rowData.price}}</span>
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
      onClick (event) {
        console.log('my-detail-row: on-click', event.target)
      }
    },
  }
</script>