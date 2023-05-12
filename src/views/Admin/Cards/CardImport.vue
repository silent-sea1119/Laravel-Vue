<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
        <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
          Import Cards
        </h2>
        <p class="mb-8 text-gray-600 dark:text-gray-400" v-if="type == 'add'">
          Issue a new card to {{ this.user.name }}.
          You've Issued {{ this.request.number_of_cards }} cards against {{ this.request.id }} Request.
        </p>
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
            form: {
                card_request_id: "",
            },
            errors: null,
            request_id: null,
            request: {},
            user: {},
            type: "add",
            selectedFile: null,
        };
    },
    mounted() {
        // get request_id from url.
        let url = new URL(window.location.href);
        let request_id = url.searchParams.get("request_id");
        // if request_id is not presend redirect to /admin/card-requests.
        if (!request_id) {
            this.$router.push("/admin/card-requests");
        }

        this.request_id = request_id;
        this.loading = true;
        this.$store.dispatch("CardRequest/getCard", request_id).then(() => {
            const error = this.$store.getters["CardRequest/error"];
            this.request = this.$store.getters["CardRequest/card"];
            
            if (error != null || !this.request.id) {
                this.$router.push("/admin/card-requests");
            }

            let user_id = this.request.user_id;
            this.form.card_request_id = this.request.id;
            this.loading = true;
            this.$store.dispatch("AdminUser/getUser", user_id).then(() => {
                const error = this.$store.getters["AdminUser/error"];
                this.user = this.$store.getters["AdminUser/user"];
                if (error != null || !this.user.id) {
                    this.$router.push("/admin/card-requests");
                }
                this.loading = false;
            });
            this.loading = false;
        });
        
    },
    methods: {
        onFileChange(e) {
            this.selectedFile = e.target.files[0];
        },
        upload() {
            this.loading = true;
            let formData = new FormData();
            formData.append("file", this.selectedFile);
            formData.append("card_request_id", this.form.card_request_id);
            this.$store.dispatch("Card/importCards", formData).then(() => {
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
                    this.$router.push("/admin/card-requests");
                });
            });
        },
    },
}
</script>
