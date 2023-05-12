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
              Email:
            </label>
            <input
              v-model="form.email"
              type="email"
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
              placeholder="Email"
            />
          </div>

          <div class="col-span-1">
            <SelectComp
              label="User Role:"
              id="new-user-role"
              name="new-user-role"
              :options="roles"
              :selected="roles.findIndex((ur) => ur.text == form.user_role)"
            />
          </div>
        </div>

        <hr class="mt-2 mb-2" v-if="errors" />
        <DisplayError :errors="errors" />
        <hr class="mt-2 mb-2" v-if="errors" />
        <ButtonView
          :disable="
            this.loading ||
            this.form.email === '' ||
            this.form.user_role === ''
          "
          @handleOnClick="manageUser"
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
import SelectComp from "@/components/SelectComp.vue";
import Swal from 'sweetalert2'
import searchFiltersHelper from "@/utils";

export default {
    name: "AddUser",
    components: {
        LoaderView,
        ButtonView,
        DisplayError,
        SelectComp,
    },
    data() {
        return {
            loading: false,
            form: {
                first_name: "",
                last_name: "",
                user_role: "alumni",
                email: "",
                reg_num: "",
            },
            roles: [
              {
                id: 1,
                text: 'admin'
              },
              {
                id: 2,
                text: 'user'
              },
            ],
            user: {},
            label: "Add User",
            errors: null,
        };
    },
    mounted() {
        // get ID from url
        const id = this.$route.params.id;
        // If ID Present but user is empty then, fetch from server.
        if (id && !this.user.id) {
          this.loading = true;
            this.label = "Edit User";
            document.title = `${this.label} - App`;
            this.$store.dispatch("AdminUser/getUser", id).then(() => {
                const error = this.$store.getters["AdminUser/error"];
                this.user = this.$store.getters["AdminUser/user"];
                if (error != null || !this.user.id) {
                    this.$router.push("/admin/user/manage");
                }
                this.form = this.user;
                this.loading = false;
                this.emitter.emit('emit-title', this.user.first_name)
            });
        }
  
        this.emitter.on('emit-update:new-user-role', this.setRole);
    },
    methods: {
        setRole(role) {
            this.form.user_role = this.roles[role].text;
        },
        manageUser() {
            if (this.label === "Add User") {
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
                text: "User Save Successfully",
                type: "success",
                confirmButtonText: "OK",
            });
            this.emitter.emit("profile-updated");
            this.$router.push("/admin/users");
        },
        addOrUpdate(type = "add") {
            this.errors = null;
            this.loading = true;
            const formData = new FormData();
            for (const key in this.form) {
                formData.append(key, this.form[key]);
            }
            if (type === "add") {
                this.$store.dispatch("AdminUser/postUser", formData).then((r) => {
                    this.errors = this.$store.getters["AdminUser/error"];
                    this.showMsg();
                });
            } else {
                const data = {
                    id: this.user.id,
                    form: formData
                }
                this.$store.dispatch("AdminUser/updateUser", data).then((r) => {
                    this.errors = this.$store.getters["AdminUser/error"];
                    this.showMsg();
                });
            }
        },
    },
}
</script>
