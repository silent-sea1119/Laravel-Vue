<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
      <div class="bg-white p-2 mt-4 mb-4">
        <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 text-center"
        >
          Create New Card
        </h2>

        <div
          class="px-4 py-3 mb-8 bg-white rounded-lg"
        >
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
              Select Package.
            </label>
            <select v-model="pkg" class="
                w-full
                px-4
                py-2
                border-2 border-gray-300
                rounded-lg
                focus:border-purple-600
                focus:outline-none
                focus:shadow-outline-purple-600
              ">
              <option value="0">Please select the package</option>
              <option value="1">Without Loaded Virtual Card</option>
              <option value="2">Loaded Virtual Card</option>
            </select>
          </div>
         <div
          class="px-4 py-3 mb-8 bg-white rounded-lg" v-if="pkg == 1">
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
              Add Quantity of VCC you want:
            </label>
            <input
              v-model="quantity_pkg1"
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
              placeholder="Enter Quantity of Cards"
              minlength="1"
              maxlength="50"
            />

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
              Amount:
            </label>
            <input
              v-model="costpkg1"
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
              disabled
            />
          </div>
          <div
          class="px-4 py-3 mb-8 bg-white rounded-lg" v-if="pkg == 2">
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
              Amount:
            </label>
            <select v-model="amount" class="
                w-full
                px-4
                py-2
                border-2 border-gray-300
                rounded-lg
                focus:border-purple-600
                focus:outline-none
                focus:shadow-outline-purple-600
              ">
              <option value="0">Please select the amount</option>
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="200">200</option>
              <option value="300">300</option>
              <option value="400">400</option>
              <option value="500">500</option>
            </select>

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
              Card Issue Fee:
            </label>
            <input
              v-model="fee"
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
              disabled
            />

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
              Add Quantity of VCC you want:
            </label>
            <input
              v-model="quantity"
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
              placeholder="Enter Quantity of Cards"
              minlength="1"
              maxlength="9"
            />

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
              Amount:
            </label>
            <input
              v-model="cost"
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
              disabled
            />
          </div>
          <LoaderView :loading="loading" />

           <div class="mt-2 mb-2 flex justify-center items-center w-full">
            <p class="text-sm bg-red-400 text-white p-4 text-center" v-if="msg">
              {{ msg }}
            </p>
          </div>
          <div class="mt-2 mb-2 flex justify-center items-center w-full">
           <p class="text-sm bg-green-400 text-white p-4 text-center" v-if="successMsg">
              {{ successMsg }}
            </p>
          </div>
           <hr class="mt-2 mb-2" />

          <div class="flex-3 mx-4 mt-2 mb-4">
            <center>
            <ButtonView
              text="Create Now"
              :disable="disaleBtn()"
              @click="requestCard"
            />
            </center>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import ButtonView from '@/components/ButtonView.vue'
import Swal from "sweetalert2";

export default {
    name: "AddImages",
    components: {
        LoaderView,
        ButtonView,
    },
    data() {
        return {
            msg: null,
            loading: false,
            pkg: 0,
            amount: 0,
            successMsg: null,
            quantity: null,
            quantity_pkg1: null,
            costpkg1: 0,
            cost: 0,
            fee: 0,
            cardFees: {
                5: 5,
                10: 5,
                20: 6,
                30: 7,
                50: 8,
                100: 10,
                200: 20,
                300: 25,
                400: 30,
                500: 35,
            },
            cardAmount: 0,
        };
    },
    watch : {
      pkg: function (val) {
        this.amount = 0;
        this.quantity = null;
        this.cost = 0;
        this.quantity_pkg1 = null;
        this.costpkg1 = 0;
      },
      quantity: function (val) {
        if (val == '' || val == null) {
          this.quantity = null;
          return;
        }
        // if its alpahbet then return
        if (isNaN(val)) {
          return;
        }
        if (val < 1) {
          this.quantity = 1;
          return;
        }
        if (val > 9) {
          this.quantity = 9;
          return;
        }
        if (val % 1 !== 0) {
          this.quantity = Math.floor(val);
          return;
        }
        //this.quantity_pkg1 = this.quantity;
        this.cost = (this.amount * val) + (this.cardFees[this.amount] * val);
        this.cost = parseFloat(this.cost).toFixed(2);
      },
      quantity_pkg1: function (val) {
        if (val == '' || val == null) {
          this.quantity_pkg1 = null;
          return;
        }
        // if its alpahbet then return
        if (isNaN(val)) {
          return;
        }
        // value should be integer.
        if (val % 1 !== 0) {
          this.quantity_pkg1 = Math.floor(val);
          return;
        }
        if (val < 1) {
          this.quantity_pkg1 = 1;
          return;
        }
        if (val > 50) {
          this.quantity_pkg1 = 50;
          return;
        }
        this.cost = (2 * val);
        this.quantity = val;
        this.costpkg1 = parseFloat(this.cost).toFixed(2);
        this.amount = this.costpkg1;
      },
      amount: function (val) {
        if (val < 1) {
          this.amount = 0;
          this.fee = 0;
          this.cost = 0;
          return; 
        }
        this.fee = this.cardFees[val];
        this.cost = (val * this.quantity) + (this.fee * this.quantity);
        this.cost = parseFloat(this.cost).toFixed(2);
      },
      successMsg(newVal) {
        if (newVal) {
          setTimeout(() => {
            this.successMsg = null;
          }, 5000);
        }
      },
      msg(newVal) {
        if (newVal) {
          setTimeout(() => {
            this.msg = null;
          }, 5000);
        }
      },
    },
    methods: {
        requestCard() {
            this.msg = null;
            this.successMsg = null;
            this.loading = true;
            
            this.$store.dispatch('User/requestCard', {
                pkg: this.pkg,
                amount: this.amount,
                quantity: this.quantity,
                quantity_pkg1: this.quantity_pkg1,
            }).then(() => {
                this.loading = false;
                let error = this.$store.getters['User/error']
                if (error) {
                    this.msg = error.error;
                    return;
                }
                this.successMsg = "Your request has been submitted successfully."
                Swal.fire({
                    title: 'Success',
                    text: "Your request has been submitted successfully.",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (this.pkg == 1) {
                          this.$router.push('/user/cards');
                        }
                    }
                })
            });
        },
      disaleBtn() {

        if (this.quantity == null || this.quantity == 0 && this.quantity_pkg1 == null || this.quantity_pkg1 == 0) {
          return true;
        } 
        if (this.amount < 1) {
          return true;
        }
        if (this.loading) {
          return true;
        }
        return false;
      },
    },
};
</script>
