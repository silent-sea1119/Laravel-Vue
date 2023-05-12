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
              Reset Password
            </h1>
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
            <div class="mt-3" v-if="form.response != null">
              <span
                class="text-red-500"
                :class="{
                  'text-green-500':
                    form.response ==
                    'Your new password has been sent to your email.',
                  'text-red-500':
                    !form.response !=
                    'Your new password has been sent to your email.',
                }"
              >
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
            <ButtonView
              :submit="form.submit"
              :disable="
                form.submit ||
                form.email.trim() === ''"
              @click="doReset"
              text="Reset Password"
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
                divClass=""
            />

            <hr class="my-8" />
            <router-link class="text-blue-600" to="/"
              >Back to Login</router-link
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="js">
import ButtonView from '@/components/ButtonView.vue';

export default {
    name: 'ResetPassword',
    components: {
      ButtonView,
    },
    data() {
        return {
            form: {
                email: '',
                submit: false,
                response: null
            }
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
        doReset() {
            if (!this.form.email.includes('@')) {
                this.form.email = '';
                return;
            }
            this.form.submit = true;
            this.form.response = null;
            this.$store.dispatch('Auth/resetPassword', this.form.email).then(response => {
                this.form.submit = false;
                this.form.response = this.$store.getters['Auth/msg'];
            }).catch(error => {
                this.form.submit = false;
                this.form.response = this.$store.getters['Auth/msg'];
            });
        }
    }
}
</script>
