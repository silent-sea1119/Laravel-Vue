<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
        <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
          Edit Profile
        </h2>
        <LoaderView :loading="loading" />

        <div class="grid grid-cols-1 md:grid-cols-1 gap-6" v-if="!loading">
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
              Current Password:
            </label>
            <input
              v-model="form.old_password"
              type="password"
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
              placeholder="Current Password"
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
              New Password:
            </label>
            <p
              class="text-red-500 text-xs italic mt-1 mb-1"
              v-if="this.form.password != '' && this.form.password.length < 6"
            >
              Password must be at least 8 characters long.
            </p>
            <input
              type="password"
              v-model="form.password"
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
              placeholder="New Password"
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
              Confiremd:
            </label>
            <p
              class="text-red-500 text-xs italic mt-1 mb-1"
              v-if="this.form.password !== this.form.password_confirmation"
            >
              Passwords do not match.
            </p>
            <input
              v-model="form.password_confirmation"
              type="password"
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
              placeholder="Confirmed"
            />
          </div>
        </div>

        <hr class="mt-2 mb-2" />
        <p class="text-red-500">{{ this.errors?.message }}</p>
        <hr class="mt-2 mb-2" />
        <ButtonView
          :disable="
            this.loading ||
            this.form.password === '' ||
            this.form.old_password === '' ||
            this.form.password.length < 6 ||
            this.form.password !== this.form.password_confirmation
          "
          @handleOnClick="update"
          text="Update"
        />
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import ButtonView from '@/components/ButtonView.vue'
import Swal from 'sweetalert2'

export default {
    name: "ChangePassword",
    components: {
        LoaderView,
        ButtonView,
    },
    data() {
        return {
            loading: false,
            form: {
                old_password: '',
                password: '',
                password_confirmation: '',
            },
            errors: null,
            user: {}
        };
    },
    mounted() {
        this.user = this.$store.getters["Auth/user"];
    },
    methods: {

        showMsg() {
            if (this.errors != null) {
                this.loading = false;
                return;
            }
            this.loading = false;
            Swal.fire({
                title: "Success",
                text: "Password Save Successfully",
                type: "success",
                confirmButtonText: "OK",
            });
            if (this.user.user_role == 'user') {
              this.$router.push("/user/dashboard");
            } else {
              this.$router.push("/admin/dashboard");
            }
        },
        update() {
            this.errors = null;
            this.loading = true;
            this.$store.dispatch("User/updatePassword", this.form).then((r) => {
                this.errors = this.$store.getters["User/error"];
                this.showMsg();
            });
            
        },
    },
}
</script>