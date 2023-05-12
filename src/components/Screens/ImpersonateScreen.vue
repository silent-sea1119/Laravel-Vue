<template>
  <div
    class="
      p-4
      mb-4
      text-sm text-red-700
      bg-red-100
      rounded-lg
      dark:bg-red-200 dark:text-red-800
    "
    role="alert"
    v-if="impersonate"
  >
    <span class="font-medium">Impersonate alert!</span> You're impersonating a
    {{ user.name }}.
    <button @click="stopImpersonating" class="text-red-500 underline">
      Stop impersonating
    </button>
  </div>
</template>

<script lang="js">
export default {
    name: "ImpersonateScreen",
    data() {
        return {
            impersonate: null,
            user: null
        }
    },
    mounted() {
        this.impersonate = JSON.parse(localStorage.getItem('impersonate'))
        this.user = this.$store.getters["Auth/user"]
    },
    methods: {
        stopImpersonating() {
            this.$store.dispatch("Auth/stopImpersonating").then(() => {
                window.location.reload()
            });
        }
    }
}
</script>
