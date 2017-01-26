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
              @vuetable:cell-clicked="onCellClicked"
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
      return `/api/aml/method/listing?user=${Laravel.userId}`
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
      console.log('cellClicked: ', field.name)
      this.$refs.vuetable.toggleDetailRow(data.id)
    }
  },
  events: {
    'filter-set' (filterText) {
      this.moreParams = {
        'filter': filterText
      }
      Vue.nextTick( () => this.$refs.vuetable.refresh())
    },
    'filter-reset' () {
      this.moreParams = {}
      this.monitoredOnly = false
      Vue.nextTick( () => this.$refs.vuetable.refresh())
    },
    'plan-allocation-used-up' (message) {
      this.message = message
      this.messageType = 'alert-warning'
      window.scrollTo(0, 0)
    },
    'success-monitor-update' (monitoredCount) {
      this.message = null
      this.messageType = null

      this.monitoredListingCount = monitoredCount
    }
  }
}
</script>
