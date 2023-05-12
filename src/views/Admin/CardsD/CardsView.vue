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
              Default Cards
            </h2>
          </div>
          <div class="flex-2">
            <LinkButton
              to="/admin/card-default/manage"
              text="Add New"
            />
          </div>
          <div class="flex-2 ml-2">
            <LinkButton
              to="/admin/card-default/import"
              text="Import"
            />
          </div>
        </div>
    

        <!-- Loading -->
        <LoaderView :loading="loading" />

        <!-- Search -->
        <AdminSearch :cols="2" @search="searchRecords" :searchFilters="filters">
        </AdminSearch>

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
                  <th class="px-4 py-3">User Name</th>
                  <th class="px-4 py-3">First Name</th>
                  <th class="px-4 py-3">Last Name</th>
                  <th class="px-4 py-3">Card Number</th>
                  <th class="px-4 py-3">Card Expire</th>
                  <th class="px-4 py-3">CVV</th>
                  <th class="px-4 py-3">Actions</th>
                </tr>
              </thead>
              <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
              >
                <template v-for="card in cards.data">
                  <!-- eslint-disable vue/require-v-for-key -->
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td
                      class="px-4 py-3 text-blue-500 cursor-pointer"
                      @click="openViewHelper(`card-${card.id}`)"
                    >
                      {{ card.id }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ card?.user?.name }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ card.first_name }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ card.last_name }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ formatCardNumber(card.card_number) }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ card.expire_month }}/ {{ card.expire_year }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ card.cvc }}
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex items-center space-x-4 text-sm">
                        <DropdownView>
                          <router-link
                            class="flex px-2 py-2 text-sm hover:bg-gray-100"
                            :to="`/admin/card/${card.id}`"
                          >
                            <i class="fas fa-eye mt-1 mx-1"></i> View
                          </router-link>
                          <router-link
                            class="
                              flex
                              px-2
                              py-2
                              text-purple-600 text-sm
                              hover:bg-gray-100
                            "
                            :to="`/admin/card/manage/${card.id}`"
                          >
                            <i class="fas fa-pencil mt-1 mx-1"></i> Edit
                          </router-link>
                          <button
                            class="
                              flex
                              px-2
                              py-2
                              text-red-600 text-sm
                              hover:bg-gray-100
                            "
                            href="javascript:void(0);"
                            @click="deleteCard(card.id)"
                          >
                            <i class="fas fa-trash mt-1 mx-1"></i> Delete
                          </button>
                        </DropdownView>
                      </div>
                    </td>
                  </tr>
                  <tr
                    class="p-0 hidden"
                    :id="`card-${card.id}`"
                    :ref="`card-${card.id}`"
                  >
                    <td colspan="6">
                      <CardView :data="card" />
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
import AdminSearch from '@/components/Search/AdminSearch.vue'
import CardView from '@/views/Admin/Cards/CardView.vue'
import DropdownView from "@/components/DropdownView";
import LinkButton from "@/components/LinkButton";

import Swal from "sweetalert2";
import {
    openViewHelper, searchFiltersHelper
} from "@/utils";

export default {
    name: 'CardsView',
    components: {
        LoaderView,
        PaginationView,
        NoRecordScreen,
        AdminSearch,
        CardView,
        DropdownView,
        LinkButton,
    },
    data() {
        return {
            loading: true,
            page: 1,
            searching: false,
            cards: [],
            search: {
                filter: '',
                type: '',
                search: '',
            },
            searchFilters: [
                'id',
                'card_number',
                'user_id',
            ],
        };
    },
    mounted: function () {
        // check if the url have get param user_id.
        let url = new URL(window.location.href);
        let user_id = url.searchParams.get('user_id');
        if (user_id) {
            this.search.filter = 'user_id';
            this.search.search = user_id;
        }
        this.fetchCards();
    },
    methods: {
        setFilter(type) {
            this.search.type = type;
            this.fetchCards();
        },
        deleteCard(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this card!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then(result => {
                if (result.value) {
                    this.$store.dispatch('Card/deleteCard', id).then(() => {
                        this.fetchCards();
                        Swal.fire(
                            'Deleted!',
                            'Card has been deleted.',
                            'success',
                        );
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'Card is safe :)',
                        'error',
                    );
                }
            });
        },
        searchRecords(search) {
            this.search = search;
            this.fetchCards();
        },
        fetchCards(page = 1) {
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
            this.$store.dispatch('Card/getCardsD', query).then(response => {
                this.cards = this.$store.getters['Card/cards'];
                this.loading = false;
            });
        },
        formatCardNumber(cardNumber) {
            if (!cardNumber) return "";
            // if it is not a number return empty string.
            if (isNaN(cardNumber)) return "";
            return cardNumber.match(/.{1,4}/g).join(" ");
        },
        openViewHelper,
    },
    computed: {
        filters() {
            return searchFiltersHelper(this.searchFilters);
        },
    }
}
</script>
