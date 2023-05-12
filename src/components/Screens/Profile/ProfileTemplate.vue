<template>
  <div class="container mx-auto my-5 mb-20 p-5">
    <!-- <LoaderView :loading="loading" /> -->
    <div class="md:flex no-wrap md:-mx-2" v-if="!loading">
      <div class="w-full md:w-3/12 md:mx-2 mt-2">
        <div class="bg-white p-3 border-t-4 border-gray-400 shadow-md">
          <div class="image overflow-hidden">
            <img
              class="h-40 w-40 mx-auto"
              :src="
                user.profile_picture ?? require('@/assets/images/avatar.png')
              "
              :alt="user.first_name"
            />
          </div>
          <h1 class="text-gray-900 font-bold text-xl leading-8 my-1">
            {{ user.first_name + " " + user.last_name }}
          </h1>
          <h3 class="text-gray-600 font-lg text-semibold leading-6">
            App International University
          </h3>
          <p class="text-sm text-gray-500 hover:text-gray-600 leading-6"></p>
          <ul
            class="
              bg-gray-100
              text-gray-600
              hover:text-gray-700 hover:shadow
              py-2
              px-3
              mt-3
              divide-y
              rounded
              shadow-sm
            "
          >
            <li class="flex items-center py-3">
              <span class="font-semibold">Status</span>
              <span class="ml-auto"
                ><span class="bg-green-500 py-1 px-2 rounded text-white text-sm"
                  >Active</span
                ></span
              >
            </li>
            <li class="flex items-center py-3">
              <strong class="font-semibold">Member since</strong>
              <span class="ml-auto px-2 py-1">{{ user.created_at }}</span>
            </li>
          </ul>
          <div class="flex flex-col my-4 max-h-min">
            <DisplayError :errors="errors" />
            <div class="m-2" v-for="m in metas" :key="m">
              <div class="flex justify-between">
                <a
                  target="_blank"
                  class="flex items-center hover:text-blue-700 w-3/4"
                >
                  <i :class="m.icon" class="mr-2"></i>
                  <p class="font-semibold truncate">
                    <span v-if="m.value" class="block truncate">{{
                      m.value
                    }}</span>
                    <span v-else>{{ m.key }}</span>
                  </p>
                </a>
                <div v-if="user.id === currentUser.id">
                  <div v-if="exists(m.key)" class="flex">
                    <a @click="toggle(m.key)" class="cursor-pointer px-2">
                      <i
                        class="
                          fa fa-pencil fa-sm
                          text-gray-600
                          hover:text-blue-700
                        "
                        aria-hidden="true"
                      ></i>
                    </a>
                    <a @click="delete_meta(m.key)" class="cursor-pointer px-2">
                      <i
                        class="
                          fa fa-trash fa-sm
                          text-gray-600
                          hover:text-red-800
                        "
                        aria-hidden="true"
                      ></i>
                    </a>
                  </div>
                  <div v-else>
                    <a @click="toggle(m.key)" class="cursor-pointer px-2">
                      <i
                        :class="
                          m.isOpen
                            ? 'fa fa-close fa-md hover:text-red-700'
                            : 'fa fa-plus hover:text-blue-700'
                        "
                        aria-hidden="true"
                      ></i>
                    </a>
                  </div>
                </div>
              </div>
              <div :class="m.isOpen ? '' : 'hidden'">
                <input
                  v-model="m.value"
                  type="text"
                  class="
                    w-full
                    mt-3
                    px-2
                    py-2
                    border-2 border-gray-300
                    rounded-lg
                    focus:border-purple-600
                    focus:outline-none
                    focus:shadow-outline-purple-600
                  "
                  :placeholder="m.key"
                />
                <div v-if="m.value.replace(/\s+/g, '') !== m.value">
                  <span class="text-red-500"> Remove WhiteSpaces </span>
                </div>
                <ButtonView
                  :disable="missingData(m.key, m.value)"
                  @handleOnClick="update_meta(m.key)"
                  text="Update"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full md:w-9/12 mx-2 mt-2">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import ButtonView from '@/components/ButtonView.vue'
import DisplayError from '@/components/DisplayError'
import Swal from 'sweetalert2'

export default {
    name: "ProfileTemplate",
    components: {
        //LoaderView,
        ButtonView,
        DisplayError,
    },
    data() {
        return {
            user: {},
            meta: [],
            loading: false,
            metas: [{
                    key: 'facebook',
                    value: '',
                    icon: 'fab fa-facebook-square fa-lg',
                    isOpen: false,
                },
                {
                    key: 'twitter',
                    value: '',
                    icon: 'fab fa-twitter-square fa-lg',
                    isOpen: false,
                },
                {
                    key: 'linkedin',
                    value: '',
                    icon: 'fab fa-linkedin fa-lg',
                    isOpen: false,
                },
                {
                    key: 'github',
                    value: '',
                    icon: 'fab fa-github fa-lg',
                    isOpen: false,
                },
                {
                    key: 'portfolio',
                    value: '',
                    icon: 'fa fa-globe fa-lg',
                    isOpen: false,
                },
            ],
            value: '',
            errors: null,
        }
    },
    mounted() {
        this.getProfile()
    },
    methods: {
        getProfile() {
            // get ID from url
            const id = this.$route.params.id;

            // If ID Present then, fetch from server.
            if (id) {
                this.loading = true;
                this.$store.dispatch("User/getProfile", id).then(() => {
                    const error = this.$store.getters["User/error"];
                    if (error != null) {
                        this.user = this.$store.getters["Auth/user"];
                        this.meta = this.$store.getters["Auth/meta"];
                    } else {
                        this.user = this.$store.getters["User/user"];
                        this.meta = this.$store.getters["User/meta"];
                    }
                    this.loading = false;
                });
            } else {
                this.$store.dispatch("Auth/getMe");
                this.user = this.$store.getters["Auth/user"];
                this.meta = this.$store.getters["Auth/meta"];
            }

            document.title = this.user.first_name + " " + this.user.last_name;

            // merge meta with metas
            for (let i = 0; i < this.metas.length; i++) {
                for (let j = 0; j < this.meta.length; j++) {
                    if (this.metas[i].key == this.meta[j].key) {
                        this.metas[i].value = this.meta[j].value;
                        break;
                    }
                }
            }
        },

        toggle(f_name) {
            let meta_field = this.metas.find((f) => f.key == f_name);
            if (meta_field.isOpen) {
                this.metas.forEach((m_field) => {
                    m_field.isOpen = false
                })
            } else {
                this.metas.forEach((m_field) => {
                    m_field.isOpen = false
                })
                meta_field.isOpen = true;
            }
        },

        exists(f_name) {
            // check if key exists in meta
            for (let i = 0; i < this.meta.length; i++)
                if (this.meta[i].key == f_name)
                    return true;
            return false;
        },

        empty_field(f_name) {

            if (this.fields[f_name])
                return false;
            return true;
        },

        showMsg(message) {

            if (this.errors != null) {
                this.loading = false;
                return;
            }
            Swal.fire({
                title: "Success",
                text: message,
                type: "success",
                confirmButtonText: "OK",
            });
        },

        missingData(key, value) {
            if (value.trim() === '' || value !== value.replace(/\s+/g, ''))
                return true;

            for (let i = 0; i < this.meta.length; i++)
                if (this.meta[i].key == key && this.meta[i].value == value)
                    return true;
        },

        update_meta(f_name) {

            this.errors = null;
            this.loading = true;

            const data = {
                id: this.user.id,
                form: {
                    'key': f_name,
                    'value': this.parseUsername(this.metas.find((f) => f.key == f_name).value),
                },
            }

            this.$store.dispatch("Alumni/updateUserMeta", data).then((r) => {
                this.errors = this.$store.getters["User/error"];
                if (!this.errors) {
                    this.$store.dispatch('Auth/getMe').then((r) => {
                        this.meta = this.$store.getters["Auth/meta"];
                    });
                }
                this.loading = false;
            });

            // Update in metas
            this.metas.find((f) => f.key == f_name).value = data.form.value;

            // Close all opened fields
            this.metas.forEach((m_field) => {
                m_field.isOpen = false;
            })

            this.showMsg('Profile Updated Successfully!');

        },

        delete_meta(f_name) {

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    this.loading = true;
                    const data = {
                        id: this.user.id,
                        key: f_name,
                    }
                    this.$store.dispatch("Alumni/deleteUserMeta", data).then((r) => {
                        this.errors = this.$store.getters["User/error"];
                        if (!this.errors) {
                            this.$store.dispatch('Auth/getMe').then((r) => {
                                this.meta = this.$store.getters["Auth/meta"];
                            });
                        }
                        this.loading = false;
                    });

                    // Close all opened fields
                    this.metas.forEach((m_field) => {
                        m_field.isOpen = false;
                    })

                    // Delete from metas
                    this.metas.find((f) => f.key == f_name).value = '';

                    this.showMsg('Profile Updated Successfully!');

                }
            });
        },

        parseUsername(url) {
            if (!url || url === '') return;
            let matches = url.match(/(?:https?:\/\/)?(?:http?:\/\/)?(?:www.)?(?:twitter|github|facebook|linkedin)?(?:.com\/)?(?:in\/)?([@a-zA-Z0-9-_.]+)/im);
            return matches.length ? matches[1] : url;
        }
    },
    computed: {
        currentUser() {
            return this.$store.getters["Auth/user"];
        },
    }
}
</script>
