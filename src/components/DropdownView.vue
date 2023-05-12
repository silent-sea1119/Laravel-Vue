<template>
  <div>
    <a
      class="text-blueGray-500 py-1 px-3"
      href="#pablo"
      ref="btnDropdownRef"
      v-on:click="toggleDropdown($event)"
    >
      <i class="fas fa-ellipsis-v"></i>
    </a>
    <div
      ref="popoverDropdownRef"
      class="
        bg-white
        text-base
        z-50
        float-left
        py-2
        list-none
        text-left
        rounded
        shadow-lg
        min-w-48
      "
      v-bind:class="{
        hidden: !dropdownPopoverShow,
        block: dropdownPopoverShow,
      }"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script lang="js">
import {
    createPopper
} from "@popperjs/core";

export default {
    name: 'DropdownView',
    data() {
        return {
            dropdownPopoverShow: false,
        };
    },
    mounted() {
        window.addEventListener("click", (e) => {
            // if the target of the click isn't the container nor a descendant of the container.
            if (
                this.$refs.btnDropdownRef !== e.target &&
                this.$refs.popoverDropdownRef !== e.target
            ) {
                this.dropdownPopoverShow = false;
            }
        });
    },
    methods: {
        toggleDropdown: function (event) {
            event.preventDefault();
            if (this.dropdownPopoverShow) {
                this.dropdownPopoverShow = false;
            } else {
                this.dropdownPopoverShow = true;
                createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
                    placement: "bottom-start",
                });
            }
        },
    },
}
</script>
