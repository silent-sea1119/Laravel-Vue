<template>
  <div class="relative">
    <button @click="togglePopover" class="focus:outline-none">
      <slot name="trigger"></slot>
    </button>
    <div v-if="showPopover" class="absolute -left-48 mt-2 ml-2 w-56 bg-white text-gray-700 border border-gray-200 rounded shadow-lg z-10">
      <div class="p-4">
        <!-- Close icon -->
        <button @click="closePopover" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 focus:outline-none">
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9.293l4.146-4.147a.5.5 0 01.708.708L10.707 10l4.147 4.146a.5.5 0 01-.708.708L10 10.707l-4.146 4.147a.5.5 0 01-.708-.708L9.293 10 5.146 5.854a.5.5 0 11.708-.708L10 9.293z" clip-rule="evenodd"/>
          </svg>
        </button>
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PopoverComp',
  data() {
    return {
      showPopover: false,
    };
  },
  methods: {
    togglePopover() {
      this.showPopover = !this.showPopover;
    },
    closePopover() {
      this.showPopover = false;
    },
  },
};
</script>
