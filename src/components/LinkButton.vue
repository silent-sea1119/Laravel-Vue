<template>
  <router-link :to="to">
    <ButtonView :text="text" />
  </router-link>
</template>

<script>
import ButtonView from '@/components/ButtonView.vue'

export default {
  name: "LinkButton",
  components: {
    ButtonView,
  },
  props: {
    to: {
      type: String,
      required: true,
    },
    text: {
      type: String,
      default: "Link",
    },
  },
};
</script>
