<template>
  <div class="home">
    <main class="h-full overflow-y-auto mt-2 mb-6">
      <div class="container px-6 mx-auto grid">
        <h2
          class="my-6 text-base font-semibold text-gray-700 dark:text-gray-200"
        >
          Card Request Info
        </h2>
        <LoaderView :loading="loading" />

        <div
          class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-2 mb-2 text-xs"
          v-if="!loading"
        >
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Requested By
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card?.user?.first_name }} {{ card?.user?.last_name }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Quantity
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.quantity }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Cost
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.cost }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Card Expire
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.expire_month }} / {{ card.expire_year }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Status
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.status }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Created - Updated At
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.created_at }} - {{ card.updated_at }}
            </span>
          </div>
        </div>

        <h2
          class="my-6 text-base font-semibold text-gray-700 dark:text-gray-200"
        >
          Cards Issued against this request:
        </h2>

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
                  <th class="px-4 py-3">Card Number</th>
                  <th class="px-4 py-3">CVV</th>
                  <th class="px-4 py-3">Expire</th>
                  <th class="px-4 py-3">Created At</th>
                </tr>
              </thead>
              <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
              >
                <template v-for="c in card.cards">
                  <!-- eslint-disable vue/require-v-for-key -->
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td
                      class="px-4 py-3 text-blue-500 cursor-pointer"
                    >
                      {{ c.id }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ c.card_number }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ c.cvc }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ c.expire_month }} / {{ c.expire_year }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ c.created_at }}
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from "@/components/LoaderView.vue";
export default {
    name: "CardView",
    components: {
        LoaderView
    },
    props: {
      data: {
        type: Object,
        default: null,
      }
    },
    data() {
        return {
            card: {},
            loading: false,
        };
    },
    mounted() {
        if (this.data) {
          this.card = this.data;
          return;
        }

        // get ID from url
        const id = this.$route.params.id;
        // If ID Present but user is empty then, fetch from server.
        if (id && !this.card.id) {
            this.loading = true;
            this.$store.dispatch("CardRequest/getCard", id).then(() => {
                const error = this.$store.getters["CardRequest/error"];
                this.card = this.$store.getters["CardRequest/card"];
                if (error != null || !this.card.id) {
                    this.$router.push("/cards");
                }
                this.loading = false;
                this.emitter.emit('emit-title', this.user.first_name)
            });
        }
    },
}
</script>
