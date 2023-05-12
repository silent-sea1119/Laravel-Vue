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
              Cards
            </h2>
          </div>
          <div class="flex-1">
          <div class="mt-6 w-full">
              <div class="flex">
                <input
                  v-model="search"
                  type="text"
                  id="search"
                  class="border-2 border-gray-300 focus:border-blue-500 focus:outline-none rounded-l-md text-gray-700 w-full py-2 pl-3 pr-10"
                  placeholder="Search Card Number"
                />
                <button
                  @click="fetchCards"
                  type="submit"
                  class="bg-purple-600 text-xs text-white font-semibold py-2 px-4 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                >
                  Submit
                </button>
              </div>
          </div>

          </div>
        </div>

        <!-- Loading -->
        <LoaderView :loading="loading" />

        <!-- Table -->
        <div class="w-full overflow-hidden rounded-lg">
          <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap bg-white rounded-lg table-shadow" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);">
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
                  "
                >
                  <th class="px-4 py-3">First Name</th>
                  <th class="px-4 py-3">Last Name</th>
                  <th class="px-4 py-3">Card Number</th>
                  <th class="px-4 py-3">Card Expire</th>
                  <th class="px-4 py-3">CVC</th>
                  <th class="px-4 py-3">Card Package</th>
                  <th class="px-4 py-3">Billing Info</th>
                </tr>
              </thead>
              <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
              >
                <template v-for="card in cards.data">
                  <!-- eslint-disable vue/require-v-for-key -->
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-md">
                      {{ card.first_name }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.last_name }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ formatCardNumber(card.card_number) }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.expire_month }}/ {{ card.expire_year }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.cvc }}
                    </td>
                    <td class="px-4 py-3 text-md">
                      {{ card.card_type }}
                    </td>
                    <!-- Address column -->
                    <td class="px-4 py-3 text-md">
                      <PopoverComp>
                        <template #trigger>
                          <span class="text-blue-500 cursor-pointer">View</span>
                        </template>
                        <div>
                          <h3 class="text-xl font-bold mb-2">Billing Info</h3>
                          <p><b>Address:</b> {{ card.address }}</p>
                          <p><b>City:</b> {{ card.city }}</p>
                          <p><b>State:</b> {{ card.state }}</p>
                          <p><b>Zip:</b> {{ card.zip }}</p>
                        </div>
                      </PopoverComp>
                    </td>

                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <!-- No Record -->
          <NoRecordScreen :show="!loading && cards.data.length == 0" />
          
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

<style scoped>
.table-shadow {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06);
}
</style>
<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import PaginationView from "@/components/PaginationView";
import NoRecordScreen from "@/components/Screens/NoRecordScreen";
import PopoverComp from "@/components/PopoverComp";

export default {
    name: 'CardsView',
    components: {
        LoaderView,
        PaginationView,
        NoRecordScreen,
        PopoverComp,
    },
    data() {
        return {
            loading: true,
            page: 1,
            searching: false,
            cards: [],
            search: null,
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
            let query = '?page=' + this.page + '&search=' + this.search;
            this.$store.dispatch('User/getCards', query).then(response => {
                this.cards = this.$store.getters['User/cards'];
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
