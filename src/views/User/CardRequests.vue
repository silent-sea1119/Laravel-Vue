<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
        <div class="flex justify-between">
 
          <!-- Page Start -->
          <div class="flex-1">
            <h2
              class="
                my-6
                text-2xl
                font-semibold
                text-gray-700
                dark:text-gray-200
              "
            >
              Order Status
            </h2>
          </div>
        </div>

        <!-- Loading -->
        <LoaderView :loading="loading" />

        <!-- Table -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
          <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
              <thead>
                <tr
                  class="
                    text-xs
                    font-semibold
                    tracking-wide
                    text-left text-gray-500
                    uppercase
                    border-b
                    dark:border-gray-700
                    bg-gray-50
                    dark:text-gray-400 dark:bg-gray-800
                  "
                >
                  <th class="px-4 py-3">#ID</th>
                  <th class="px-4 py-3">Package</th>
                  <th class="px-4 py-3">Quantity</th>
                  <th class="px-4 py-3">Amount</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Comment</th>
                  <th class="px-4 py-3">Created At</th>
                </tr>
              </thead>
              <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
              >
                <template v-for="card in cards.data">
                  <!-- eslint-disable vue/require-v-for-key -->
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td
                      class="px-4 py-3 text-blue-500 cursor-pointer text-md"
                    >
                      {{ card.id }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.package }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.quantity }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.amount }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      <span :class="{
                        'bg-blue-500': card.status === 'pending',
                        'bg-green-500': card.status === 'completed',
                        'bg-red-500': card.status === 'denied',
                        'text-white': card.status === 'pending' || card.status === 'completed' || card.status === 'denied',
                        'py-1': true,
                        'px-2': true,
                        'rounded': true,
                      }">
                        <!-- Change denied to cancel if text is denied. -->
                        {{ card.status === 'denied' ? 'cancel' : card.status }}
                      </span>
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.comment }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.created_at }}
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <!-- No Record -->
          <NoRecordScreen :show="!loading && cards?.data?.length == 0" />
          
          <!-- Pagination -->
          <div
            class="
              grid
              px-4
              py-3
              text-xs
              font-semibold
              tracking-wide
              text-gray-500
              uppercase
              border-t
              dark:border-gray-700
              bg-gray-50
              sm:grid-cols-9
              dark:text-gray-400 dark:bg-gray-800
            "
          >
            <span class="flex items-center col-span-3"> </span>
            <span class="col-span-2"></span>
            <PaginationView
              :page="page"
              :pagination="cards"
              @fetchRecords="fetchCards"
            />
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import PaginationView from "@/components/PaginationView";
import NoRecordScreen from "@/components/Screens/NoRecordScreen";

export default {
    name: 'CardRequests',
    components: {
        LoaderView,
        PaginationView,
        NoRecordScreen,
    },
    data() {
        return {
            loading: true,
            page: 1,
            searching: false,
            cards: [],
        };
    },
    mounted: function () {
        this.fetchCards();
    },
    methods: {
        fetchCards(page = 1) {
            // check if page is object.
            if (typeof page === 'object') {
                page = this.page;
            }
            this.page = page || 1;
            this.cards = [];
            this.loading = true;
            // let query = Object.keys(this.search)
            //     .map(key => key + '=' + this.search[key])
            //     .join('&');
            let query = '?page=' + this.page;
            this.$store.dispatch('User/getCardRequests', query).then(response => {
                this.cards = this.$store.getters['User/requests'];
                this.loading = false;
            });
        },
        formatCardNumber(cardNumber) {
            if (!cardNumber) return "";
            // if it is not a number return empty string.
            if (isNaN(cardNumber)) return "";
            return cardNumber.match(/.{1,4}/g).join(" ");
        },
    },
}
</script>
