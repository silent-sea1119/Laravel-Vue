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
              {{ user.first_name }} {{ user.last_name }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Email
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ user.email }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              User Role
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ user.user_role }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Balance
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ user?.balance || 0 }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Number Of Cards
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ user?.num_of_cards || 0 }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Created - Updated At
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ user.created_at }} - {{ user.updated_at }}
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
    name: "UserView",
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
            user: {},
            loading: false,
        };
    },
    mounted() {
        if (this.data) {
          this.user = this.data;
          return;
        }

        // get ID from url
        const id = this.$route.params.id;
        // If ID Present but user is empty then, fetch from server.
        if (id && !this.user.id) {
            this.loading = true;
            this.$store.dispatch("AdminUser/getUser", id).then(() => {
                const error = this.$store.getters["AdminUser/error"];
                this.user = this.$store.getters["AdminUser/user"];
                if (error != null || !this.user.id) {
                    this.$router.push("/users");
                }
                this.loading = false;
                this.emitter.emit('emit-title', this.user.first_name)
            });
        }
    },
}
</script>
