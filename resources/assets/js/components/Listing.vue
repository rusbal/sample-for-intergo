<template>
    <div v-show="isLoaded">
        <div class="table-responsive" v-show="withContent">
            <h3 class="pull-left">{{ head }}</h3>
            <div class="pull-right">Total: {{ totalRows }}</div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Seller SKU</th>
                    <th>ASIN</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Monitor</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="listing in listings">
                    <td>{{ listing.seller_sku }}</td>
                    <td><a :href="amazonListingUrl(listing)" target="_blank">{{ listing.asin }}</a></td>
                    <td>{{ listing.item_name }}</td>
                    <td class="text-right">{{ listing.quantity_available }}</td>
                    <td class="text-center"><input @change="setWillMonitor(listing)" type="checkbox" v-model="listing.will_monitor"></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div v-show="isEmpty">
            <div v-if="issetAmazonMws" class="alert alert-default">
                <slot name="amazon-requesting"></slot>
            </div>

            <div v-else>
                <div class="alert alert-danger">Amazon keys not set or values are incorrect.</div>
                <slot name="amazon-mws-link"></slot>
            </div>
        </div>

    </div>
</template>

<script>
export default{
    props: ['head'],
    data(){
        return{
            isLoaded: false,
            listings: []
        }
    },
    methods: {
        amazonListingUrl(listing) {
            return "https://www.amazon.com/gp/offer-listing/" + listing.asin
        },
        setWillMonitor(listing) {
            axios.patch(
                Laravel.route('', listing.id),
                { will_monitor: listing.will_monitor ? 1 : 0 }
            );
        }
    },
    computed: {
        totalRows() {
            return this.listings.length
        },
        issetAmazonMws() {
            return Laravel.issetAmazonMws
        },
        withContent() {
            return this.listings.length > 0
        },
        isEmpty() {
            return this.listings.length == 0
        }
    },
    mounted() {
        Laravel.http
            .get('method.listing')
            .then((response) => {
                this.isLoaded = true
                this.listings = response.data
            });
    }
}
</script>
