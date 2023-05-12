<template>
  <div class="home">
    <main class="h-full overflow-y-auto mt-2 mb-6">
      <div class="container px-6 mx-auto grid">
        <h2
          class="my-6 text-base font-semibold text-gray-700 dark:text-gray-200"
        >
          User Info
        </h2>
        <LoaderView :loading="loading" />

        <div
          class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-2 mb-2 text-xs"
          v-if="!loading"
        >
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Name
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.first_name }} {{ card.last_name }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Card Number
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ formatCardNumber(card.card_number) }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Card CVV
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.cvc }}
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
              Address
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.address }}
            </span>
          </div>
         <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              City
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.city }}
            </span>
          </div>
         <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              State
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.state }}
            </span>
          </div>
         <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Zip
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ card.zip }}
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
            this.$store.dispatch("Card/getCard", id).then(() => {
                const error = this.$store.getters["Card/error"];
                this.card = this.$store.getters["Card/card"];
                if (error != null || !this.card.id) {
                    this.$router.push("/cards");
                }
                this.loading = false;
                this.emitter.emit('emit-title', this.user.first_name)
            });
        }
    },
    methods: {
        formatCardNumber(cardNumber) {
            if (!cardNumber) return "";
            // if it is not a number return empty string.
            if (isNaN(cardNumber)) return "";
            return cardNumber.match(/.{1,4}/g).join(" ");
        },
    }
}
</script>
