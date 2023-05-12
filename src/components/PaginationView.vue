<template>
<span class="flex col-span-4 mt-2 mx-2 sm:mt-auto sm:justify-end">
    <nav aria-label="Table navigation">
        <ul class="inline-flex items-center">
            <li :disabled="page == 1">
                <button class="
              px-3
              py-1
              rounded-md
              focus:outline-none focus:shadow-outline-purple hover:underline
            " @click="prevPage()">
                    Previous
                </button>
            </li>
            <li v-for="item in generatePaginationList(pagination)" :key="item">
                <button class="
              px-3
              py-1
              rounded-md
              focus:outline-none focus:shadow-outline-purple mx-1
            " :class="{
              'bg-purple-600': item == p,
              'text-white': item == p,
              'bg-gray-100': item != p,
              'text-gray-600': item != p,
            }" @click="switchPage(item)">
                    {{ item }}
                </button>
            </li>

            <li>
                <button class="
              px-3
              py-1
              rounded-md
              focus:outline-none focus:shadow-outline-purple hover:underline
            " @click="nextPage()" :disabled="pagination.current_page === pagination.last_page">
                    Next
                </button>
            </li>
        </ul>
    </nav>
</span>
</template>

<script lang="js">
import {
    generatePaginationList
} from "../utils";

export default {
    name: "PaginationView",
    props: {
        pagination: {
            type: Object,
            required: true,
        },
        page: {
            type: Number,
            default: 1,
        },
    },
    data() {
        return {
            p: this.page,
        };
    },
    methods: {
        generatePaginationList,
        switchPage(page) {
            if (page === '...') {
                return false;
            }
            this.p = page;
            this.$emit("fetchRecords", this.p);
        },
        nextPage() {
            this.p++;
            this.$emit("fetchRecords", this.p);
        },
        prevPage() {
            this.p--;
            this.$emit("fetchRecords", this.p);
        },
    }
}
</script>
