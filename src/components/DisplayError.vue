<template>
  <div v-if="errors" class="text-red-500 py-2 px-4 pr-0 rounded font-bold mb-4">
    <div v-for="(v, k) in this.errors" :key="k">
      <p v-for="error in v" :key="error" class="text-sm">
        {{ error }}
      </p>
    </div>
  </div>
</template>

<script lang="js">
export default {
    name: "DisplayError",
    props: {
        errors: {
            type: Object,
            default: null,
        }
    },
}
</script>
