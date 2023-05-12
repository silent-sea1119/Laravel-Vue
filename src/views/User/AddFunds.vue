<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
      <div class="bg-white p-2 mt-4 mb-4">
        <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 text-center"
        >
          Add Funds
        </h2>

        <div
          class="px-4 py-3 mb-8 bg-white rounded-lg"
        >
            <label
              class="
                block
                text-sm
                font-medium
                leading-5
                text-gray-700
                dark:text-gray-200
              "
            >
              Amount:
            </label>
            <input
              v-model="amount"
              type="number"
              class="
                w-full
                px-4
                py-2
                border-2 border-gray-300
                rounded-lg
                focus:border-purple-600
                focus:outline-none
                focus:shadow-outline-purple-600
              "
              placeholder="Enter Amount between 5-700"
              min="5"
              max="700"
            />
          </div>

          <div class="mt-2 mb-2 flex justify-center items-center w-full">
           <p class="text-sm bg-red-400 text-white p-4 text-center" v-if="error">
              {{ error }}
            </p>
          </div>

          <LoaderView :loading="loading" />

         <center>
          <div class="flex-3 mx-4 mt-2 mb-4">
          <ButtonView
            text="Pay With Bitcoin"
            :disable="amount < 5 || amount > 700 || loading"
            @click="buyFunds"
          />
          </div>
          <p class="text-red-400 text-sm px-4">
            <strong>Note:</strong> We do not charge any fee and we accept multiple cryptocurrencies BTC, USDT, ETH, LTC, etc
          </p>
          </center>
        </div>
        </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import ButtonView from '@/components/ButtonView.vue'

export default {
    name: "AddImages",
    components: {
        LoaderView,
        ButtonView,
    },
    data() {
        return {
            loading: false,
            amount: null,
            error: null
        };
    },
    watch: {
        amount() {
            this.error = null;
            if (this.amount < 5) {
                this.error = 'Amount must be greater than 4';
            } else if (this.amount > 700) {
                this.error = 'Amount must be less than 700';
            }
        },
        error(newVal) {
          if (newVal) {
            setTimeout(() => {
              this.error = null;
            }, 3000);
          }
        }
    },
    methods: {
        buyFunds() {
            this.loading = true;
            
            this.$store.dispatch('User/addFundToUser', {
                amount: this.amount,
            }).then(() => {
                this.loading = false;
                let redirectedURL = this.$store.getters['User/redirectedURL'];
                if (redirectedURL != null || redirectedURL != 'null') {
                    window.location.href = redirectedURL.redirectedURL;
                }
            });
        },
    },
};
</script>
