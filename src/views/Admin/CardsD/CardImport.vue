<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
        <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
          Import Default Cards
        </h2>

        <LoaderView :loading="loading" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" v-if="!loading">
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
              Select CSV File:
            </label>
            <input
              type="file"
              @change="onFileChange"
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
              placeholder="File"
            />
          </div>
                

        <hr class="mt-2 mb-2" v-if="errors" />
        <DisplayError :errors="errors" />
        <hr class="mt-2 mb-2" v-if="errors" />
        <ButtonView
          :disable="
            this.loading ||
            this.selectedFile == null
          "
          @handleOnClick="upload"
          text="Import"
        />
      </div>
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import ButtonView from '@/components/ButtonView.vue'
import DisplayError from '@/components/DisplayError.vue'
import Swal from 'sweetalert2'
import searchFiltersHelper from "@/utils";

export default {
    name: "manageCard",
    components: {
        LoaderView,
        ButtonView,
        DisplayError,
    },
    data() {
        return {
            loading: false,
            errors: null,
            request_id: null,
            request: {},
            user: {},
            type: "add",
            selectedFile: null,
        };
    },

    methods: {
        onFileChange(e) {
            this.selectedFile = e.target.files[0];
        },
        upload() {
            this.loading = true;
            let formData = new FormData();
            formData.append("file", this.selectedFile);
            this.$store.dispatch("Card/importCardsD", formData).then(() => {
                const error = this.$store.getters["Card/error"];
                if (error != null) {
                    this.errors = error;
                    this.loading = false;
                    return;
                }
                this.loading = false;
                Swal.fire({
                    title: "Success",
                    text: "Cards imported successfully.",
                    icon: "success",
                    confirmButtonText: "Ok",
                }).then(() => {
                    this.$router.push("/admin/default-cards");
                });
            });
        },
    },
}
</script>
