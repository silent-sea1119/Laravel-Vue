<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
          <div class="flex justify-between">
          <!-- Page Start -->
          <div class="flex-1">
              <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
          {{ label }}
        </h2>
          </div>
          <div class="flex-2">
            <LinkButton
              to='/admin/card-default/import'
              text="Import"
            />
          </div>
        </div>
          
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
              First Name:
            </label>
            <input
              type="text"
              v-model="form.first_name"
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
              placeholder="First Name"
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
              Last Name:
            </label>
            <input
              type="text"
              v-model="form.last_name"
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
              placeholder="Last Name"
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
      Card Number:
    </label>
    <div class="relative">
      <input
        v-model="formattedCardNumber"
        type="text"
        class="
          w-full
          px-4
          py-2
          pr-12
          border-2 border-gray-300
          rounded-lg
          focus:border-purple-600
          focus:outline-none
          focus:shadow-outline-purple-600
        "
        placeholder="Number"
        maxlength="19"
      />
      <div class="absolute inset-y-0 right-0 flex items-center pr-4">
        <!-- Add your card icon SVG or library here -->
        <i class="fas fa-credit-card"></i>
      </div>
    </div>
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
              Card CVV:
            </label>
            <input
              v-model="form.cvc"
              type="number"
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
              placeholder="Card CVV"
              maxlength="3"
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
              Card Expire Month:
            </label>
            <input
              v-model="form.expire_month"
              type="number"
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
              placeholder="Expire Month"
              maxlength="2"
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
              Card Expire Year:
            </label>
            <input
              v-model="form.expire_year"
              type="number"
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
              placeholder="Expire Year"
              maxlength="4"
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
              Address:
            </label>
            <input
              v-model="form.address"
              type="text"
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
              placeholder="address"
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
              City:
            </label>
            <input
              v-model="form.city"
              type="text"
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
              placeholder="City"
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
              State:
            </label>
            <input
              v-model="form.state"
              type="text"
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
              placeholder="State"
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
              Zip:
            </label>
            <input
              v-model="form.zip"
              type="number"
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
              placeholder="Zip"
            />
          </div>
        </div>
        

        <hr class="mt-2 mb-2" v-if="errors" />
        <DisplayError :errors="errors" />
        <hr class="mt-2 mb-2" v-if="errors" />
        <ButtonView
          :disable="
            this.loading ||
            this.form.card_number === ''
            || this.form.cvc === ''
            || this.form.expire_month === ''
            || this.form.expire_year === ''
          "
          @handleOnClick="manageCard"
          :text="label"
        />
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import ButtonView from '@/components/ButtonView.vue'
import DisplayError from '@/components/DisplayError.vue'
import LinkButton from '@/components/LinkButton.vue'

import Swal from 'sweetalert2'
import searchFiltersHelper from "@/utils";

export default {
    name: "manageCard",
    components: {
        LoaderView,
        ButtonView,
        DisplayError,
        LinkButton,
    },
    data() {
        return {
            loading: false,
            form: {
                first_name: "",
                last_name: "",
                card_number: "",
                cvc: "",
                expire_month: "",
                expire_year: "",
                address: "",
                city: "",
                state: "",
                zip: "",
            },
            card: {},
            label: "Add Card",
            errors: null,
            request_id: null,
            request: {},
            user: {},
            type: "add",
        };
    },
    methods: {
        manageCard() {
            if (this.label === "Add Card") {
                this.addOrUpdate();
                return;
            }
            this.addOrUpdate("update");
        },
        showMsg() {
            if (this.errors != null) {
                this.loading = false;
                return;
            }
            Swal.fire({
                title: "Success",
                text: "Card Save Successfully",
                type: "success",
                confirmButtonText: "OK",
            });
            this.emitter.emit("profile-updated");
            this.$router.push("/admin/default-cards");
        },
        addOrUpdate(type = "add") {
            this.errors = null;
            this.loading = true;
            const formData = new FormData();
            for (const key in this.form) {
                formData.append(key, this.form[key]);
            }
            if (type === "add") {
                this.$store.dispatch("Card/postCardD", formData).then((r) => {
                    this.errors = this.$store.getters["Card/error"];
                    this.showMsg();
                });
            } else {
                const data = {
                    id: this.card.id,
                    form: formData
                }
                this.$store.dispatch("Card/updateCard", data).then((r) => {
                    this.errors = this.$store.getters["Card/error"];
                    this.showMsg();
                });
            }
        },
        formatCardNumber(cardNumber) {
          if (!cardNumber) return "";
          // if it is not a number return empty string.
          if (isNaN(cardNumber)) return "";
          return cardNumber.match(/.{1,4}/g).join(" ");
        },
    },
    computed: {
      formattedCardNumber: {
        get() {
          return this.formatCardNumber(this.form.card_number);
        },
        set(newValue) {
          this.form.card_number = newValue.replace(/\s+/g, "");
        },
      },
    }
}
</script>
