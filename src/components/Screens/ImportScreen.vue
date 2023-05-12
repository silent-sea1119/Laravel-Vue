<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
        <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
          {{ label }}
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
              Select File:
            </label>
            <div class="flex items-center justify-center w-full">
              <label
                class="
                  flex flex-col
                  w-full
                  h-32
                  border-4 border-blue-200 border-dashed
                  hover:bg-gray-100 hover:border-gray-300
                "
              >
                <div class="flex flex-col items-center justify-center pt-7">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-gray-400 group-hover:text-gray-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    />
                  </svg>
                  <p
                    class="
                      pt-1
                      text-sm
                      tracking-wider
                      text-gray-400
                      group-hover:text-gray-600
                    "
                  >
                    Attach a file
                  </p>
                </div>
                <input
                  @change="onChangeInput"
                  type="file"
                  class="opacity-0"
                  id="formFile"
                  placeholder="File"
                  accept=".csv"
                />
              </label>
            </div>
            <div class="bg-gray-300 p-2" v-if="fileName">
              {{ this.fileName }}
              <i
                class="fa fa-close cursor-pointer text-red-500"
                @click="removeFile"
              ></i>
            </div>
          </div>
        </div>

        <hr class="mt-2 mb-2" />
        <DisplayError :errors="errors" />
        <hr class="mt-2 mb-2" />
        <ButtonView
          :disable="this.loading || !this.fileName"
          @handleOnClick="importData"
          text="Import"
        />
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import ButtonView from '@/components/ButtonView.vue'
import DisplayError from '@/components/DisplayError.vue'
import Swal from 'sweetalert2'

export default {
    name: "ImportScreen",
    components: {
        LoaderView,
        ButtonView,
        DisplayError,
    },
    props: {
        label: {
            type: String,
            required: true,
        },
        loading: {
            type: Boolean,
            required: true,
        },
        errors: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            form: {
                name: "",
            },
            fileName: null,
        };
    },
    methods: {
        onChangeInput(event) {
            const file = event.target.files[0];
            // Check file extension.
            if (file.name.split('.').pop() !== 'csv') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a CSV file!',
                })
                event.target.value = '';
                return;
            }
            this.form = file;
            this.fileName = file.name;
        },
        removeFile() {
            this.form = null;
            this.fileName = null;
        },
        importData() {
            this.$emit('importData', this.form);
        },
    },
}
</script>
