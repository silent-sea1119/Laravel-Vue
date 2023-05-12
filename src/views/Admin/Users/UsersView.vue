<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
        <div class="flex justify-between">
          <!-- Page Start -->
          <div class="flex-1">
            <h2
              class="
                my-6
                text-2xl
                font-semibold
                text-gray-700
                dark:text-gray-200
              "
            >
              Users
            </h2>
          </div>
          <div class="flex-2">
            <LinkButton
              to="/admin/user/manage"
              text="Add New"
            />
          </div>
        </div>

        <!-- Loading -->
        <LoaderView :loading="loading" />

        <!-- Search -->
        <AdminSearch :cols="3" @search="searchRecords" :searchFilters="filters">
          <div class="col-span-1 bg-gray-50">
            <SelectComp
              label="Role:"
              id="role-filter"
              name="role-filter"
              :options="roleFilters"
              v-model="search.type"
              :selected="search.type"
            />
          </div>
        </AdminSearch>

        <!-- Table -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
          <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
              <thead>
                <tr
                  class="
                    text-xs
                    font-semibold
                    tracking-wide
                    text-left text-gray-500
                    uppercase
                    border-b
                    dark:border-gray-700
                    bg-gray-50
                    dark:text-gray-400 dark:bg-gray-800
                  "
                >
                  <th class="px-4 py-3">#ID</th>
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Email</th>
                  <th class="px-4 py-3">Balance</th>
                  <th class="px-4 py-3">Number Of Cards</th>
                  <th class="px-4 py-3">Role</th>
                  <th class="px-4 py-3">Actions</th>
                </tr>
              </thead>
              <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
              >
                <template v-for="user in users.data">
                  <!-- eslint-disable vue/require-v-for-key -->
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td
                      class="px-4 py-3 text-blue-500 cursor-pointer"
                      @click="openViewHelper(`user-${user.id}`)"
                    >
                      {{ user.id }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <p class="font-semibold">
                          {{ user.first_name + " " + user.last_name }}
                        </p>
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ user.email }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ user?.balance }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ user?.num_of_cards }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ user.user_role }}
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex items-center space-x-4 text-sm">
                        <DropdownView>
                          <router-link
                            class="flex px-2 py-2 text-sm hover:bg-gray-100"
                            :to="`/admin/user/${user.id}`"
                          >
                            <i class="fas fa-eye mt-1 mx-1"></i> View
                          </router-link>
                          <router-link
                            class="
                              flex
                              px-2
                              py-2
                              text-purple-600 text-sm
                              hover:bg-gray-100
                            "
                            :to="`/admin/user/manage/${user.id}`"
                          >
                            <i class="fas fa-pencil mt-1 mx-1"></i> Edit
                          </router-link>
                          <router-link
                            class="
                              flex
                              px-2
                              py-2
                              text-blue-600 text-sm
                              hover:bg-gray-100
                            "
                            :to="`/admin/transactions/${user.id}`"
                          >
                            <i class="fas fa-pencil mt-1 mx-1"></i> 
                            Transactions
                          </router-link>
                          <router-link
                            class="
                              flex
                              px-2
                              py-2
                              text-pink-600 text-sm
                              hover:bg-gray-100
                            "
                            :to="`/admin/cards?user_id=${user.id}`"
                          >
                            <i class="fas fa-pencil mt-1 mx-1"></i> 
                            Cards
                          </router-link>
                          <button
                            class="
                              flex
                              px-2
                              py-2
                              text-purple-600 text-sm
                              hover:bg-gray-100
                            "
                            href="javascript:void(0);"
                            @click="addFunds(user.id)"
                          >
                            <i class="fas fa-trash mt-1 mx-1"></i> Add Funds
                          </button>
                          <button
                            class="
                              flex
                              px-2
                              py-2
                              text-red-600 text-sm
                              hover:bg-gray-100
                            "
                            href="javascript:void(0);"
                            @click="deleteUser(user.id)"
                          >
                            <i class="fas fa-trash mt-1 mx-1"></i> Delete
                          </button>

                          <button
                            class="
                              flex
                              px-2
                              py-2
                              text-red-400 text-sm
                              hover:bg-gray-100
                            "
                            href="javascript:void(0);"
                            @click="markUser(user.id, 'suspend')"
                            v-if="user.suspend_at == null"
                          >
                            <i class="fa fa-map-marker mt-1 mx-1"></i> Suspend
                          </button>

                          <button
                            class="
                              flex
                              px-2
                              py-2
                              text-green-400 text-sm
                              hover:bg-gray-100
                            "
                            href="javascript:void(0);"
                            @click="markUser(user.id, 'unsuspend')"
                            v-if="user.suspend_at != null"
                          >
                            <i class="fa fa-map-marker mt-1 mx-1"></i> Reactivate
                          </button>
                        </DropdownView>
                      </div>
                    </td>
                  </tr>
                  <tr
                    class="p-0 hidden"
                    :id="`user-${user.id}`"
                    :ref="`user-${user.id}`"
                  >
                    <td colspan="6">
                      <UserView :data="user" />
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
          <!-- No Record -->
          <NoRecordScreen :show="!loading && users.data.length == 0" />

          <!-- Pagination -->
          <div
            class="
              grid
              px-4
              py-3
              text-xs
              font-semibold
              tracking-wide
              text-gray-500
              uppercase
              border-t
              dark:border-gray-700
              bg-gray-50
              sm:grid-cols-9
              dark:text-gray-400 dark:bg-gray-800
            "
          >
            <span class="flex items-center col-span-3"> </span>
            <span class="col-span-2"></span>
            <PaginationView
              :page="page"
              :pagination="users"
              @fetchRecords="fetchUsers"
            />
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import PaginationView from "@/components/PaginationView";
import DropdownView from "@/components/DropdownView";
import NoRecordScreen from "@/components/Screens/NoRecordScreen";
import LinkButton from "@/components/LinkButton";
import UserView from "./UserView"
import AdminSearch from '@/components/Search/AdminSearch.vue'
import SelectComp from "@/components/SelectComp.vue";

import Swal from "sweetalert2";
import {
    openViewHelper, searchFiltersHelper
} from "@/utils";

export default {
    name: 'UsersView',
    components: {
        LoaderView,
        DropdownView,
        PaginationView,
        NoRecordScreen,
        LinkButton,
        UserView,
        AdminSearch,
        SelectComp,
    },
    data() {
        return {
            loading: true,
            page: 1,
            searching: false,
            users: [],
            search: {
                filter: '',
                type: '',
                search: '',
            },
            searchFilters: [
                'id',
                'first_name',
                'last_name',
                'email',
            ],
        };
    },
    mounted: function () {
        this.fetchUsers();
        this.emitter.on('emit-update:role-filter', this.setFilter);
    },
    methods: {
        addFunds(id) {
          // swal alert with amount input box.
          Swal.fire({
            title: "Add Funds",
            text: "Enter amount to add",
            input: "number",
            showCancelButton: true,
            confirmButtonText: "Add",
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
              return this.$store.dispatch('AdminUser/addFunds', {
                id: id,
                amount: amount
              }).then(() => {
                Swal.fire({
                  title: "Funds Added!",
                  text: "User funds has been added.",
                  type: "success",
                  confirmButtonText: "OK"
                });
                this.fetchUsers();
              });
            },
            allowOutsideClick: () => !Swal.isLoading()
          });
        },
        setFilter(type) {
            this.search.type = type;
            this.fetchUsers();
        },
        markUser(id, mark) {
            this.loading = true;
            this.users = [];
            this.$store.dispatch('AdminUser/markUser', {
                id: id,
                mark: mark
            }).then(() => {
                Swal.fire({
                    title: "Status Change!",
                    text: "User status has been changed.",
                    type: "success",
                    confirmButtonText: "OK"
                });
                this.fetchUsers();
            });
        },
        deleteUser(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    this.$store.dispatch('AdminUser/deleteUser', id).then(() => {
                        Swal.fire({
                            title: "Deleted!",
                            text: "User has been deleted.",
                            type: "success",
                            confirmButtonText: "OK"
                        });
                        this.fetchUsers();
                    });
                }
            });
        },
        searchRecords(search) {
            this.search = search;
            this.fetchUsers();
        },
        fetchUsers(page = 1) {
            // check if page is object.
            if (typeof page === 'object') {
                page = this.page;
            }
            this.page = page || 1;
            this.users = [];
            this.loading = true;
            let query = Object.keys(this.search)
                .map(key => key + '=' + this.search[key])
                .join('&');
            query += '&page=' + this.page;
            this.$store.dispatch('AdminUser/getUsers', query).then(response => {
                this.users = this.$store.getters['AdminUser/users'];
                this.loading = false;
            });
        },
        openViewHelper,
    },
    computed: {
        currentUser() {
            return this.$store.getters['Auth/user'];
        },
        filters() {
            return searchFiltersHelper(this.searchFilters);
        },
        roleFilters () {
          return searchFiltersHelper(['admin', 'user'])
        }
    }
}
</script>
