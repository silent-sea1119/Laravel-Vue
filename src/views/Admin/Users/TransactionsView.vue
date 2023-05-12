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
              Transactions
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
                  <th class="px-4 py-3">Amont</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Type</th>
                  <th class="px-4 py-3">Created At</th>
                </tr>
              </thead>
              <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
              >
                <template v-for="transaction in transactions.data">
                  <!-- eslint-disable vue/require-v-for-key -->
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td
                      class="px-4 py-3 text-blue-500 cursor-pointer"
                    >
                      {{ transaction.id }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ transaction.amount }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ transaction.status }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ transaction.type }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ transaction.created_at }}
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <!-- No Record -->
          <NoRecordScreen :show="!loading && transactions.data.length == 0" />
          
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
              :pagination="transactions"
              @fetchRecords="fetchTransactions"
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
    name: 'CardsView',
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
            transactions: [],
            search: {
                filter: '',
                type: '',
                search: '',
            },
            searchFilters: [
                'id',
                'user_id',
            ],
        };
    },
    mounted: function () {
        // if user_id is in the query params.
        if (this.$route.params.id) {
            this.search.search = this.$route.params.id;
            this.search.filter = 'user_id';
        }
        this.fetchTransactions();
    },
    methods: {
        fetchTransactions(page = 1) {
            // check if page is object.
            if (typeof page === 'object') {
                page = this.page;
            }
            this.page = page || 1;
            this.cards = [];
            this.loading = true;
            let query = Object.keys(this.search)
                .map(key => key + '=' + this.search[key])
                .join('&');
            query += '&page=' + this.page;
            this.$store.dispatch('Admin/getTransactions', query).then(response => {
                this.transactions = this.$store.getters['Admin/transactions'];
                this.loading = false;
            });
        },
    },
}
</script>
