<template>
  <main>
    <label for="toggle" class="text-xs text-gray-700">
      {{ label }}
    </label>
    <div
      class="
        relative
        inline-block
        w-12
        mr-2
        align-middle
        select-none
        transition
        duration-200
        ease-in
      "
    >
      <input
        type="checkbox"
        class="
          switch-button
          absolute
          block
          w-6
          h-6
          bg-white
          border-4
          rounded-full
          appearance-none
          cursor-pointer
        "
        id="switch"
        v-model="isChecked"
        :disabled="disabled"
        :class="switchClass"
        @input="updateValue"
      />
      <label
        for="switch"
        class="
          switch-label
          block
          overflow-hidden
          h-6
          bg-gray-300
          cursor-pointer
          rounded-full
        "
        :class="labelClass"
      ></label>
    </div>
  </main>
</template>

<script>
export default {
  name: "SwitchButton",
  props: {
    label: {
      type: String,
      default: "Toggle me.",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    modelValue: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isChecked: this.modelValue,
    };
  },
  methods: {
    updateValue(e) {
      this.$emit("update:modelValue", e.target.checked);
    },
  },
  computed: {
    switchClass() {
      let checked = this.isChecked
        ? "right-0 border-purple-600"
        : "border-gray-300";
      let disabled = this.disabled
        ? "opacity-25 cursor-not-allowed"
        : "cursor-pointer";
      return `${checked} ${disabled}`;
    },
    labelClass() {
      return this.isChecked ? "bg-purple-600" : "";
    },
  },
};
</script>
