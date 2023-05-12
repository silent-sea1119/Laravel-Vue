<template>
    <button
      @click="
        () => {
          this.$emit('handleOnClick');
        }
      "
      :disabled="disable"
      :class="
        disable
          ? `${_class} opacity-25 cursor-not-allowed`
          : `${_class} cursor-pointer`
      "
    >
      {{ text }}
    </button>
</template>

<script lang="js">
export default {
    name: "ButtonView",
    props: {
        divClass: {
            type: String,
            default: "flex-3 mx-4 mt-2 mb-4"
        },
        _class: {
            type: String,
            default: "px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
        },
        text: {
            type: String,
            default: "Button"
        },
        link: {
            type: String,
            default: null
        },
        disable: {
            type: Boolean,
            default: false
        }
    },
}
</script>
