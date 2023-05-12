<template>
  <div
    class="
      md:flex
      sm:block
      text-gray-700
      border border-gray-200
      rounded-sm
      bg-white
      m-3
      dark:bg-gray-800 dark:border-gray-700
      shadow-sm shadow-gray-200
    "
  >
    <ul
      class="
        flex flex-wrap
        -mb-px
        text-sm
        font-medium
        text-center 
        text-gray-500
        dark:text-gray-400
      "
    >
      <slot></slot>
    </ul>
  </div>
</template>

<script lang="js">
export default {
    name: "TabLayout",
}
</script>
