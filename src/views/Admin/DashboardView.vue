<template>
  <div class="home">
    <main class="h-full overflow-y-auto mt-3 mx-3">
      <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Welcome Back
      </h2>
      <LoaderView :loading="loading" />
      <div
        class="grid gap-6 mb-8 grid-cols-2 md:grid-cols-2 xl:grid-cols-4"
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
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from "@/components/LoaderView.vue";

export default {
    name: "DashboardView",
    components: {
        LoaderView,
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
                this.$store.dispatch("Admin/getDashboard").then(() => {
                    this.items = this.$store.getters["Admin/items"];
                    this.loading = false;
                });
        }
    }
}
</script>