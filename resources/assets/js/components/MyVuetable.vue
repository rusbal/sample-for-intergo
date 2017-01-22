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
</style>

<template>
  <div class="ui">

    <filter-bar></filter-bar>

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
  data () {
    return {
      fields: Fields.Listing,
      moreParams: {}
    }
  },
  computed: {
    apiUrl() {
      return `/api/aml/method/listing?user=${Laravel.userId}`
    }
  },
  methods: {
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
      Vue.nextTick( () => this.$refs.vuetable.refresh())
    }
  }
}
</script>
