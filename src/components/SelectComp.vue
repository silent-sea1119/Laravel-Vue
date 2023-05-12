<template>
  <div class="rounded-md">
    <div>
      <label
        :for="id"
        class="
          block
          text-sm
          font-medium
          leading-5
          text-gray-700
          dark:text-gray-200
        "
        >{{ label }}
        <span v-if="requires == true" class="text-red-400">*</span>:
      </label>
      <select
        :ref="id"
        :id="id"
        :name="name"
        :class="error ? 'border-red-500' : ''"
        @input="updateValue"
        :placeholder="label"
        :multiple="multiple"
        :disabled="disabled"
      >
        <option v-for="option in options" :value="option.id" :key="option">
          {{ option.text }}
        </option>
      </select>
      <span class="text-red-500 p-2 mt-3" v-if="error"> {{ error }} </span>
    </div>
  </div>
</template>

<script lang="js">
import "selectize";
import $ from 'jquery';
import 'selectize/dist/css/selectize.css';

export default {
    name: "SelectComp",
    props: {
        options: {
            type: Array,
            required: true,
        },
        id: {
            type: String,
            default: "email"
        },
        name: {
            type: String,
            required: true,
        },
        placeholder: {
            type: String,
            default: "",
        },
        modelValue: {
            type: String,
            default: null,
        },
        label: {
            type: String,
            default: "",
        },
        error: {
            type: String,
            default: "",
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        requires: {
            type: Boolean,
            default: true,
        },
        selected: {
            type: Number,
            default: null,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    mounted() {
        const $select = $(this.$refs[this.id]);
        $select.selectize({
            create: false,
            highlight: true,
            hideSelected: true,
            closeAfterSelect: true,
        });
        // disable default selected option.
        $select[0].selectize.setValue(this.selected);

        $select.on('change', (e) => {
            this.updateValue(e)
        });
    },
    methods: {
        updateValue(e) {
            this.emitter.emit('emit-update:' + this.id, e.target.value);
        }
    },
}
</script>
