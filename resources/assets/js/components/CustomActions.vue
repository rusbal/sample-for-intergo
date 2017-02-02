<template>
    <div class="custom-actions">

        <button v-if="rowData.will_monitor === 1"
                class="ui green button"
                @click="itemAction('uncheck-item', rowData, rowIndex)">
            <i class="checkmark icon"></i>
        </button>

        <button v-else-if="rowData.will_monitor === 0"
                class="ui button"
                @click="itemAction('check-item', rowData, rowIndex)">
            <i class="square outline icon"></i>
        </button>

        <button v-else class="ui yellow button">
            <i class="spinner icon"></i>
        </button>

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
      itemAction (action, data, index) {

        this.$events.fire('open-monitor-form', data)

//      let hold_monitor = data.will_monitor
//      data.will_monitor = null
//
//      axios.patch(
//        '/ajax/aml/monitor/' + data.id,
//        { will_monitor: hold_monitor ? 0 : 1 }
//
//      ).then((response) => {
//          if (response.data.success) {
//              data.will_monitor = hold_monitor ? 0 : 1
//              this.$events.fire('success-monitor-update', response.data.monitoredListingCount)
//          } else {
//              data.will_monitor = hold_monitor
//              this.$events.fire('plan-allocation-used-up', response.data.message)
//          }
//
//      }).catch((response) => data.will_monitor = hold_monitor);

      }
    }
  }
</script>

<style>
    .custom-actions button.ui.button {
      padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
      margin: auto !important;
    }
</style>