<style>
    .custom-actions button.ui.button {
      padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
      margin: auto !important;
    }
</style>

<template>
    <div class="custom-actions">

        <div v-if="rowData.will_monitor === 1" class="">
            <button class="ui outline button"
                @click="openMonitorForm(rowData)">
                <i class="edit icon"></i>
            </button>

            <button class="ui green button"
                    @click="unMonitorItem(rowData)">
                <i class="checkmark icon"></i>
            </button>
        </div>

        <button v-else-if="rowData.will_monitor === 0"
                class="ui button"
                @click="openMonitorForm(rowData)">
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
      unMonitorItem(data) {
        this.$events.fire('un-monitor-item', data)
      },
      openMonitorForm(data) {
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
