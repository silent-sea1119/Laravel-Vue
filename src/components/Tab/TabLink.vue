<template>
  <li class="mr-2" :id="id">
    <a
      href="#!"
      @click="onClick"
      class="
        inline-flex
        p-4
        rounded-t-lg
        border-b-2 border-transparent
        hover:text-blue-500 hover:border-blue-500
        dark:hover:text-gray-300
        group
      "
      :class="{ 'text-blue-500 border-blue-500': active }"
    >
      <i class="mr-2 mt-1" :class="icon"></i> {{ label }}
    </a>
  </li>
</template>

<script lang="js">
export default {
    name: "TabLink",
    props: {
        id: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            default: 'Link'
        },
        icon: {
            type: String,
            default: 'fa fa-link'
        },
        active: {
            type: Boolean,
            default: false,
        }
    },
    methods: {
        onClick(e) {
            e.preventDefault();
            e.stopPropagation();
            this.$emit('click', this.id);
        }
    }
}
</script>
