<template>
  <div class="mt-2 mb-2">
    <div :class="gridClasses">
      <div class="col-span-1">
        <SelectComp
          label="Filter by:"
          id="search"
          name="search"
          :options="searchFilters"
          v-model="search.filter"
          :selected="search.filter"
        />
      </div>
      <div class="col-span-1">
        <label
          class="
            block
            text-sm
            font-medium
            leading-5
            text-gray-700
            dark:text-gray-200
          "
        >
          Search:
        </label>
        <input
          v-model="search.search"
          class="
            w-full
            px-4
            py-2
            border-2 border-gray-300
            rounded-lg
            focus:border-purple-600
            focus:outline-none
            focus:shadow-outline-purple-600
          "
          placeholder="Search..."
          @keyup.enter="submitSearch"
        />
      </div>

      <!-- Anything can be inserted here -->
      <slot></slot>
    </div>
  </div>
</template>

<script lang="js">
import SelectComp from "@/components/SelectComp.vue";

export default {
    name: "AdminSearch",
    components: {
        SelectComp
    },
    props: {
        searchFilters: {
            type: Array,
            required: true
        },
        cols: {
            type: Number,
            default: 2
        }
    },
    data() {
        return {
            search: {
                search: "",
                filter: "id"
            },
        };
    },
    watch: {
        'search.filter': function (val) {
            this.submitSearch();
        },
    },
    mounted() {
        this.emitter.on('emit-update:search', this.setFilter);
    },
    methods: {
        setFilter(filter) {
            this.search.filter = filter;
        },
        submitSearch() {
            this.$emit("search", this.search);
        }
    },
    computed: {
      gridClasses() {
        return `grid grid-cols-1 md:grid-cols-${this.cols} gap-6`;
      }
    }
}
</script>
