<style>
.semantic {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
tr.vuetable-detail-row {
  background: rgba(0, 0, 0, 0.05);
}
.bold-notice {
  font-weight: bold;
  font-size: 1.1em;
}
</style>

<template>
  <div class="ui">

    <!--<button id="show-modal" @click="showModal = true">Show Modal</button>-->

    <!-- use the modal component, pass in the prop -->
    <!--<modal v-if="showModal" @close="showModal = false">-->
        <!--<h3 slot="header">ray header</h3>-->
        <!--<h3 slot="body">usbal body</h3>-->
        <!--<template slot="footer">-->
            <!--<button class="modal-primary-button btn btn-primary" @click="$emit('close')"> Monitor </button>-->
            <!--<button class="modal-default-button btn btn-default" @click="showModal = false"> Cancel </button>-->
        <!--</template>-->
    <!--</modal>-->

    <div v-show="message" class="alert text-left dismissible bold-notice" :class="messageType">
        {{ message }}
        <div class="m-t-10"><a href="/my/subscription" class="text-left">Click here to upgrade your plan.</a></div>
    </div>

    <div class="clearfix">
        <div class="pull-left">
            <filter-bar></filter-bar>
        </div>
        <div class="pull-right m-t-20">
            <div class="checkbox">
                <label>
                    <input type="checkbox" @change="viewMonitoredOnly" v-model="monitoredOnly">
                    <span class="darkgreen"> {{ monitoredListingCount }} monitored items </span>
                </label>
            </div>
        </div>
    </div>

    <div class="vuetable-pagination ui basic segment grid">
      <vuetable-pagination-info ref="paginationInfoTop"></vuetable-pagination-info>
      <vuetable-pagination ref="paginationTop" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
    </div>

    <vuetable ref="vuetable"
              :api-url="apiUrl"
              :append-params="moreParams"
              :fields="fields"
              :multi-sort="true"
              :per-page="100"
              @vuetable:pagination-data="onPaginationData"
              detail-row-component="my-detail-row"
              pagination-path=""
    ></vuetable>

    <div class="vuetable-pagination ui basic segment grid">
      <vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>
      <vuetable-pagination ref="pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
    </div>

  </div>
</template>

<script>
import Vuetable               from 'vuetable-2/src/components/Vuetable.vue'
import VuetablePagination     from 'vuetable-2/src/components/VuetablePagination.vue'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo.vue'
import Fields                 from './fields/Listing.js'

import Vue from 'vue'
import CustomActions          from './CustomActions.vue'

Vue.component('custom-actions', CustomActions)

import DetailRow              from './DetailRow.vue'
Vue.component('my-detail-row', DetailRow)

import FilterBar              from './FilterBar.vue'
Vue.component('filter-bar', FilterBar)

import VueEvents              from 'vue-events'
Vue.use(VueEvents)

Vue.component('modal', { template: '#modal-template' })

export default {
  components: {
    Vuetable,
    VuetablePagination,
    VuetablePaginationInfo,
    Fields
  },
  props: ['monitoredListing'],
  data () {
    return {
      showModal: false,
      monitoredListingCount: 0,
      fields: Fields.Listing,
      moreParams: {},
      message: null,
      messageType: null,
      monitoredOnly: false
    }
  },
  mounted() {
    this.monitoredListingCount = this.monitoredListing
  },
  computed: {
    apiUrl() {
      return '/ajax/aml/listing'
    }
  },
  methods: {
    viewMonitoredOnly() {
        if (this.monitoredOnly) {
            this.moreParams.monitor = 1
        } else {
            delete this.moreParams.monitor
        }
        Vue.nextTick( () => this.$refs.vuetable.refresh())
    },
    onPaginationData (paginationData) {
      this.$refs.paginationTop.setPaginationData(paginationData)
      this.$refs.paginationInfoTop.setPaginationData(paginationData)

      this.$refs.pagination.setPaginationData(paginationData)
      this.$refs.paginationInfo.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    },
    onCellClicked (data, field, event) {
      this.$refs.vuetable.toggleDetailRow(data.id)
    },
    warningMessage(message) {
        this.message = message
        this.messageType = 'alert-warning'
        window.scrollTo(0, 0)
    },
    clearMessage() {
        this.message = null
        this.messageType = null
    }
  },
  events: {
    'un-monitor-item' (data) {

       /**
        * AJAX: Unmonitor
        */

        axios.patch(
           '/ajax/aml/monitor/' + data.id,
           { will_monitor: 0 }

        ).then((response) => {
            if (response.data.success) {
                this.clearMessage()
                this.monitoredListingCount = response.data.monitoredListingCount
                data.will_monitor = 0

            } else {
                this.warningMessage(response.data.message)
            }

        }).catch((response) => {
            alert('Failed to un-monitor this item.  Please try again.')
        });
    },
    'open-monitor-form' (data) {
        this.$refs.vuetable.toggleDetailRow(data.id)
    },
    'submit-monitor-form' (data) {

       /**
        * AJAX: Monitor and set map, moq
        *
        * Note: Input is validated in DetailRow.vue
        */

        axios.patch(
            '/ajax/aml/monitor/' + data.id,
            {
                will_monitor: 1,
                minimum_advertized_price: data.minimumAdvertizedPrice,
                maximum_offer_quantity: data.maximumOfferQuantity
            }

        ).then((response) => {
            if (response.data.success) {
                this.clearMessage()
                this.monitoredListingCount = response.data.monitoredListingCount

                this.$refs.vuetable.toggleDetailRow(data.id)

                data.will_monitor = 1
                data.maximum_offer_quantity = data.maximumOfferQuantity
                data.minimum_advertized_price = data.minimumAdvertizedPrice

            } else {
                this.warningMessage(response.data.message)
            }

        }).catch((response) => {
            alert('Failed to save your inputs.  Please try again.')
        });
    },
    'cancel-monitor-form' (data) {
        this.$refs.vuetable.toggleDetailRow(data.id)
    },
    'filter-set' (filterText) {
        this.moreParams.filter = filterText
        Vue.nextTick( () => this.$refs.vuetable.refresh())
    },
    'filter-reset' () {
        delete this.moreParams.filter
        Vue.nextTick( () => this.$refs.vuetable.refresh())
    },
    'close' () {
        alert('closing popup')
    }
  }
}
</script>
