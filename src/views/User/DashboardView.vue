<template>
  <div class="home">
    <main class="h-full overflow-y-auto mt-3 mx-3">
      <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 text-center">
        Welcome Back
      </h2>
      <LoaderView :loading="loading" />

      <div
        class="flex justify-center items-center space-x-6 mb-8"
        v-if="!loading"
      >
        <div
          class="
            flex
            items-center
            p-4
            bg-white
            rounded-lg
            shadow-xs
            dark:bg-gray-800
            w-full
            md:w-1/2
            xl:w-1/4
            shadow-lg
          "
          v-for="(item, idx) in items?.data"
          :key="idx"
        >
          <div class="p-3 mr-4 rounded-full" :class="item.color">
            <i :class="item.icon"></i>
          </div>
          <div>
            <p
              class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
              {{ item.label }}
            </p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
              {{ item.count }}
            </p>
          </div>
        </div>
      </div>

      <p class="text-center text-black dark:text-black mt-2 mb-2">
        Create your Virtual Card instantly and make payments worldwide, <br /> don't use for any illigle work.
      </p>
    
        <div class="flex flex-col items-center space-y-2">
          <LinkButton
            to='/user/create-card'
            text="Create Virtual Card"
          />
          <LinkButton
            to='/user/cards'
            text="View Your Cards"
          />
          <LinkButton
            to='/user/add-fund'
            text="Add Funds"
          />
        </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from "@/components/LoaderView.vue";
import LinkButton from "@/components/LinkButton.vue";

export default {
    name: "DashboardView",
    components: {
        LoaderView,
        LinkButton,
    },
    data() {
        return {
            items: {},
            loading: false,
            user: {},
        };
    },
    mounted() {
        this.getDashboard()
        this.user = this.$store.getters["Auth/user"];
    },
    methods: {
        getDashboard() {
            this.loading = true,
                this.$store.dispatch("User/getDashboard").then(() => {
                    this.items = this.$store.getters["User/items"];
                    this.loading = false;
                });
        }
    }
}
</script>