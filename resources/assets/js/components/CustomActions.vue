<template>
    <div class="custom-actions">
        <button v-if="rowData.will_monitor" class="ui green button" @click="itemAction('uncheck-item', rowData, rowIndex)"><i class="checkmark icon"></i></button>
        <button v-else class="ui button" @click="itemAction('check-item', rowData, rowIndex)"><i class="square outline icon"></i></button>
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

        axios.patch(
          Laravel.route('', data.id),
          { will_monitor: data.will_monitor ? 0 : 1 }
        ).then((response) => {
          if (response.data.success) {
            data.will_monitor = ! data.will_monitor
          }
        });

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