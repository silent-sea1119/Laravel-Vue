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
              Jobs
            </h2>
          </div>
          <div class="flex-2">
            <div v-if="role === 'admin' || role === 'superadmin'">
              <router-link
                to="/admin/job/manage"
                v-if="allowedPermissions.includes('admin.jobs.create')"
              >
                <ButtonView text="Add" />
              </router-link>
            </div>
            <div v-else-if="role === 'alumni'">
              <router-link to="/job/manage">
                <ButtonView text="Add" />
              </router-link>
            </div>
          </div>
        </div>

        <!-- Loading -->
        <LoaderView :loading="loading" />
        <!-- Search -->
        <AdminSearch @search="searchRecords" :searchFilters="filters" />

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
                  <th class="px-4 py-3">Title</th>
                  <th class="px-4 py-3">Company</th>
                  <th class="px-4 py-3">Added By</th>
                  <th class="px-4 py-3">Location</th>
                  <th class="px-4 py-3">Created At</th>
                  <th class="px-4 py-3">Actions</th>
                </tr>
              </thead>
              <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
              >
                <template v-for="job in jobs.data">
                  <!-- eslint-disable vue/require-v-for-key -->
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-blue-500 cursor-pointer" @click="openViewHelper(`job-${job.id}`)">
                      {{ job.id }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ job.title }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                      {{ job.company }}
                    </td>
                    <td class="px-4 py-3 text-sm text-blue-600">
                      <router-link :to="`/admin/user/${job.user.id}`">
                        {{ job.user.first_name }} {{ job.user.last_name }}
                      </router-link>
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ job.location }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ job.created_at }}
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex items-center space-x-4 text-sm">
                        <DropdownView>
                          <div v-if="role === 'alumni'">
                            <router-link
                              class="flex px-2 py-2 text-sm hover:bg-gray-100"
                              :to="`/job/${job.id}`"
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
                              :to="`/job/manage/${job.id}`"
                            >
                              <i class="fas fa-pencil mt-1 mx-1"></i> Edit
                            </router-link>
                            <button
                              class="
                                flex
                                px-2
                                py-2
                                text-red-600 text-sm
                                hover:bg-gray-100
                              "
                              href="javascript:void(0);"
                              @click="deleteJob(job.id)"
                            >
                              <i class="fas fa-trash mt-1 mx-1"></i> Delete
                            </button>
                          </div>
                          <div v-else>
                            <router-link
                              class="flex px-2 py-2 text-sm hover:bg-gray-100"
                              :to="`/admin/job/${job.id}`"
                              v-if="
                                allowedPermissions.includes('admin.jobs.show')
                              "
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
                              :to="`/admin/job/manage/${job.id}`"
                              v-if="
                                allowedPermissions.includes('admin.jobs.update')
                              "
                            >
                              <i class="fas fa-pencil mt-1 mx-1"></i> Edit
                            </router-link>
                            <button
                              class="
                                flex
                                px-2
                                py-2
                                text-red-600 text-sm
                                hover:bg-gray-100
                              "
                              href="javascript:void(0);"
                              @click="deleteJob(job.id)"
                              v-if="
                                allowedPermissions.includes(
                                  'admin.jobs.destroy'
                                ) || role === 'alumni'
                              "
                            >
                              <i class="fas fa-trash mt-1 mx-1"></i> Delete
                            </button>
                          </div>
                        </DropdownView>
                      </div>
                    </td>
                  </tr>
                  <tr
                    class="p-0 hidden"
                    :id="`job-${job.id}`"
                    :ref="`job-${job.id}`"
                  >
                    <td colspan="6">
                      <ViewJobScreen :data="job" />
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <!-- No Record -->
          <NoRecordScreen :show="!loading && jobs.data.length == 0" />

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
              :pagination="jobs"
              @fetchRecords="fetchJobs"
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
import DropdownView from "@/components/DropdownView.vue";
import NoRecordScreen from "@/components/Screens/NoRecordScreen";
import ButtonView from "@/components/ButtonView";
import ViewJobScreen from "./ViewJobScreen";
import AdminSearch from '@/components/Search/AdminSearch.vue';

import Swal from "sweetalert2";
import {
    openViewHelper, searchFiltersHelper
} from "@/utils";

export default {
    name: "JobScreen",
    components: {
        LoaderView,
        DropdownView,
        PaginationView,
        NoRecordScreen,
        ButtonView,
        ViewJobScreen,
        AdminSearch,
    },
    data() {
        return {
            loading: true,
            role: '',
            page: 1,
            searching: false,
            jobs: [],
            search: {
                filter: '',
                type: '',
                search: '',
            },
            searchFilters: [
                'id',
                'title',
                'company',
                'user_id'
            ],
            allowedPermissions: [],
        }
    },
    mounted: function () {
        this.allowedPermissions = this.$store.getters['Auth/permissions'];
        this.role = this.$store.getters['Auth/user'].user_role;
        this.fetchJobs();
    },
    methods: {
        deleteJob(id) {
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
                    this.$store.dispatch('Job/deleteJob', id).then(() => {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Job has been deleted.",
                            type: "success",
                            confirmButtonText: "OK"
                        });
                        this.fetchJobs();
                    });
                }
            });
        },
        searchRecords(search) {
            this.search = search;
            this.fetchJobs();
        },
        fetchJobs(page = 1) {
            // check if page is object.
            if (typeof page === 'object') {
                page = this.page;
            }
            this.page = page || 1;
            this.jobs = [];
            this.loading = true;
            let query = Object.keys(this.search)
                .map(key => key + '=' + this.search[key])
                .join('&');
            query += '&page=' + this.page;
            
            this.$store.dispatch('Job/getJobs', query).then(response => {
                this.jobs = this.$store.getters['Job/jobs'];
                this.loading = false;
          });
        },
        openViewHelper,
    },
    computed: {
        filters() {
            return searchFiltersHelper(this.searchFilters);
        }
    }
}
</script>
