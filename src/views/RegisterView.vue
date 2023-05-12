<template>
  <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
    <div
      class="
        flex-1
        h-full
        max-w-4xl
        mx-auto
        overflow-hidden
        bg-white
        rounded-lg
        shadow-xl
        dark:bg-gray-800
      "
    >
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="h-32 md:h-auto md:w-1/2">
          <img
            aria-hidden="true"
            class="object-cover w-full h-full dark:hidden"
            src="@/assets/img/logo.jpg"
            alt="Office"
          />
        </div>

        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
          <div class="w-full">
            <h1
              class="
                mb-4
                text-xl
                font-semibold
                text-gray-700
                dark:text-gray-200
              "
            >
              Create An Account
            </h1>
            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">First Name</span>
              <input
                class="
                  appearance-none
                  rounded-none
                  relative
                  block
                  w-full
                  px-3
                  mt-2
                  py-2
                  border border-gray-300
                  placeholder-gray-500
                  text-gray-900
                  rounded-t-md
                  focus:outline-none
                  focus:ring-indigo-500
                  focus:border-indigo-500
                  focus:z-10
                  sm:text-sm
                "
                placeholder="Enter First Name here"
                v-model="form.first_name"
              />
            </label>
            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">Last Name</span>
              <input
                class="
                  appearance-none
                  rounded-none
                  relative
                  block
                  w-full
                  px-3
                  mt-2
                  py-2
                  border border-gray-300
                  placeholder-gray-500
                  text-gray-900
                  rounded-t-md
                  focus:outline-none
                  focus:ring-indigo-500
                  focus:border-indigo-500
                  focus:z-10
                  sm:text-sm
                "
                placeholder="Enter Last Name here"
                v-model="form.last_name"
              />
            </label>
            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">Email</span>
              <input
                class="
                  appearance-none
                  rounded-none
                  relative
                  block
                  w-full
                  px-3
                  mt-2
                  py-2
                  border border-gray-300
                  placeholder-gray-500
                  text-gray-900
                  rounded-t-md
                  focus:outline-none
                  focus:ring-indigo-500
                  focus:border-indigo-500
                  focus:z-10
                  sm:text-sm
                "
                placeholder="Enter email here"
                v-model="form.email"
              />
            </label>
            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Password</span>
              <input
                class="
                  appearance-none
                  rounded-none
                  relative
                  block
                  w-full
                  px-3
                  mt-2
                  py-2
                  border border-gray-300
                  placeholder-gray-500
                  text-gray-900
                  rounded-t-md
                  focus:outline-none
                  focus:ring-indigo-500
                  focus:border-indigo-500
                  focus:z-10
                  sm:text-sm
                "
                placeholder="***************"
                type="password"
                v-model="form.password"
              />
            </label>

            <div class="mt-3" v-if="form.response != null">
              <span class="text-red-500">
                {{ form.response }}
              </span>
            </div>
            <div v-if="form.submit">
              <div
                style="border-top-color: transparent"
                class="
                  w-8
                  h-8
                  border-4 border-purple-600 border-dotted
                  rounded-full
                  animate-spin
                  mt-3
                "
              ></div>
            </div>
              <hr class="mt-2 mb-2" v-if="errors" />
              <DisplayError :errors="errors" />
              <hr class="mt-2 mb-2" v-if="errors" />
            <ButtonView
              :submit="form.submit"
              :disable="
                form.submit ||
                form.email.trim() === '' ||
                form.password.trim() === ''
              "
              @click="signup"
              text="Sign Up"
              _class="
               block
                w-full
                px-4
                py-2
                mt-4
                text-sm
                font-medium
                leading-5
                text-center text-white
                transition-colors
                duration-150
                bg-purple-600
                border border-transparent
                rounded-lg
                active:bg-purple-600
                hover:bg-purple-700
                focus:outline-none focus:shadow-outline-purple
                cursor-pointer
                mt-2"
            />
            <hr class="my-8" />
            <router-link class="text-blue-600" to="/"
              >Already have an account</router-link
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="js">
import ButtonView from '@/components/ButtonView.vue';
import Swal from 'sweetalert2'
import DisplayError from '@/components/DisplayError.vue'

export default {
    name: 'LoginView',
    components: {
        ButtonView,
        DisplayError,
    },
    data() {
        return {
            form: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                submit: false,
                response: null
            },
            errors: null,
        }
    },
    watch: {
        'form.email': function (val) {
            this.form.response = null;
            if (!val.includes('@')) {
                this.form.response = 'Invalid email address';
            }
        },
    },
    methods: {
        showMsg() {
            if (this.errors != null) {
                this.loading = false;
                return;
            }
            Swal.fire({
                title: "Success",
                text: "Your account has been register successfully, please verify your email.",
                type: "success",
                confirmButtonText: "OK",
            });
            this.$router.push("/");
        },
        signup() {
            if (!this.form.email.includes('@')) {
                this.form.email = '';
                return;
            }
            this.form.submit = true;
            this.form.response = null;
            // login via Auth store.
            this.$store.dispatch('Auth/postRegister', this.form).then(response => {
                this.form.submit = false;
                this.errors = this.$store.getters['Auth/error'];
                this.showMsg();
            }).catch(error => {
                this.form.submit = false;
                this.form.response = this.$store.getters['Auth/msg'];
            });
        }
    }
}
</script>
