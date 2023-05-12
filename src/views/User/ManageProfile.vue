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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" v-if="!loading">
          <div class="col-span-1">
            <label :class="label_style"> First Name: </label>
            <input
              type="text"
              v-model="form.first_name"
              :class="field_style"
              placeholder="First Name"
            />
          </div>
          <div class="col-span-1">
            <label :class="label_style"> Last Name: </label>
            <input
              type="text"
              v-model="form.last_name"
              :class="field_style"
              placeholder="Last Name"
            />
          </div>
          <div class="col-span-1">
            <label :class="label_style"> Email: </label>
            <input
              v-model="form.email"
              type="email"
              :class="field_style"
              placeholder="Email"
              :disabled="currentUser.user_role == 'user'"
            />
          </div>
        </div>

        <hr class="mt-2 mb-2" />
        <DisplayError :errors="errors" />
        <hr class="mt-2 mb-2" />
        <ButtonView
          :disable="this.loading || this.form.email === ''"
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
import DisplayError from '@/components/DisplayError.vue'
import Swal from 'sweetalert2'

export default {
    name: "ManageProfile",
    components: {
        LoaderView,
        ButtonView,
        DisplayError,
    },
    data() {
        return {
            loading: false,
            label_style: 'block text-sm font-medium leading-5 text-gray-700 dark:text-gray-200',
            field_style: 'w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none focus:shadow-outline-purple-600',
            form: {
                first_name: "",
                last_name: "",
                email: "",
            },
            user: {},
            errors: null,
            loading2: false,
        };
    },
    mounted() {
        this.user = this.$store.getters["Auth/user"];
        this.form = this.user;
    },
    methods: {
        showMsg() {
            if (this.errors != null) {
                this.loading = false;
                return;
            }
            Swal.fire({
                title: "Success",
                text: "Profile Save Successfully",
                type: "success",
                confirmButtonText: "OK",
            });
            this.emitter.emit("profile-updated");
            if (this.user.user_role == 'user') {
              this.$router.push("/user/dashboard");
            } else {
              this.$router.push("/admin/dashboard");
            }
        },
        update() {
            this.errors = null;
            this.loading = true;
            this.$store.dispatch("User/updateUser", this.form).then((r) => {
                this.errors = this.$store.getters["User/error"];
                this.showMsg();
            });
        },
    },
    computed: {
      currentUser() {
        return this.$store.getters["Auth/user"];
      },
    }
}
</script>
