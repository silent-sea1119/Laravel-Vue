<template>
  <div class="sticky hidden" id="chec-div" ref="chec">
    <div
      class="
        mt-4
        ml-6
        lg:mr-4
        md:w-1/2
        lg:w-1/3
        rounded-lg
        grid grid-cols-1
        bg-gray-100
        p-3
        absolute
        top-[45px]
        right-10
        border-box
        shadow-2xl
        border-2 border-gray-300
        transform
        translate-x-0
        transition
        ease-in-out
        duration-700
      "
      id="notification"
      ref="notification"
    >
      <div
        class="
          w-full
          flex
          items-center
          justify-between
          flex-wrap
          p-2
          border-b-2
          mb-2
        "
      >
        <span>
          <h1
            class="
              focus:outline-none
              md:text-2xl
              text-sm
              font-semibold
              leading-6
              text-gray-800
            "
          >
            Notifications
          </h1>
        </span>
        <span
          class="text-gray-800 cursor-pointer"
          @click="closeNotificationPanel"
        >
          <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M18 6L6 18"
              stroke="#4B5563"
              stroke-width="1.25"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M6 6L18 18"
              stroke="#4B5563"
              stroke-width="1.25"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </span>
      </div>

      <LoaderView :loading="loading" />
      <div class="mt-2 w-full bg-gray-100 rounded-md">
        <div
          class="
            shadow
            border-gray-400
            w-full
            py-2
            border-b-2
            grid grid-cols-7
            items-start
            gap-2
            my-2
            bg-white
            cursor-pointer
          "
          v-for="notification in notifications.data"
          :key="notification"
          @click="goTo(notification)"
        >
          <div
            class="w-full h-full flex flex-col items-center justify-center ml-2"
          >
            <div>
              <i :class="notification.icon" class="text-indigo-500"></i>
            </div>
          </div>
          <div class="text-black md:col-span-5 col-span-4 pt-1 sm:ml-2">
            <p class="leading-tight text-sm text-wrap">
              {{ notification.message }}
            </p>
            <span class="text-xs text-gray-500 -pt-2">
              {{ notification.created_at }}
            </span>
          </div>
          <div
            class="
              w-full
              h-full
              flex flex-col
              items-center
              justify-center
              group
              cursor-pointer
            "
          >
            <div>
              <i
                class="ml-2 fa fa-eye text-gray-500 group-hover:text-blue-500"
                @click="readNotification(notification.id)"
              ></i>
            </div>
          </div>
        </div>
      </div>
      <NoRecordScreen
        message="No notification."
        :show="!loading && notifications?.data?.length == 0"
      />
      <div class="mt-4 border-t-2 py-2" v-if="notifications?.data?.length > 10">
        <PaginationView
          :page="page"
          :pagination="pagination"
          @fetchRecords="fetchNotifications"
        />
      </div>
    </div>
  </div>
</template>

<script lang="js">
import LoaderView from '@/components/LoaderView.vue'
import PaginationView from "@/components/PaginationView";
import NoRecordScreen from "@/components/Screens/NoRecordScreen";
import Swal from "sweetalert2";

export default {
    name: "NotificationComp",
    components: {
        LoaderView,
        PaginationView,
        NoRecordScreen,
    },
    data() {
        return {
            loading: true,
            page: 1,
            notifications: [],
            pagination: {
                total: 0,
                per_page: 12,
                current_page: 1,
                last_page: 0,
                from: 1,
                to: 12
            },
        }
    },
    mounted() {
        if (this.$store.getters['Auth/login']) {
          this.fetchNotifications()
          this.emitter.on("notificationHandler", (show) => {
              this.notificationHandler(show);

              if (show == true) {
                  this.fetchNotifications();
              }
          });
        }
    },
    methods: {
        fetchNotifications(page = 1) {
            // check if page is object.
            if (typeof page === 'object') {
                page = this.page;
            }
            this.page = page || 1;
            this.notifications = [];
            let query = 'page=' + this.page
            this.loading = true;
            this.$store.dispatch('Notification/getNotifications', query).then((response) => {
                this.loading = false;
                let notifications = this.$store.getters['Notification/notifications'];
                this.notifications = notifications;
                this.pagination = notifications
                this.loading = false;
            }).catch((error) => {
                this.loading = false;
            });
        },
        closeNotificationPanel() {
            this.emitter.emit("closeNotificationPanel");
            this.notificationHandler(false);
        },

        goTo(notification) {

            this.$store.dispatch('Notification/markAsRead', notification.id).then(() => {
                this.closeNotificationPanel();
                this.$router.push(notification.url);
            });
        },
        readNotification(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Read it!"
            }).then(result => {
                if (result.value) {
                    this.$store.dispatch('Notification/markAsRead', id).then(() => {
                        Swal.fire({
                            title: "Read!",
                            text: "Notification has marked as read.",
                            type: "success",
                            confirmButtonText: "OK"
                        });
                        this.fetchNotifications();
                    });
                }
            });
        },
        deleteNotification(id) {
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
                    this.$store.dispatch('Notification/deleteNotification', id).then(() => {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Notification has been deleted.",
                            type: "success",
                            confirmButtonText: "OK"
                        });
                        this.fetchNotifications();
                    });
                }
            });
        },
        notificationHandler(show) {
            let notification = this.$refs.notification;
            let checdiv = this.$refs.chec;
            if (!show) {
                notification.classList.add("translate-x-full");
                notification.classList.remove("translate-x-0");
                setTimeout(function () {
                    checdiv.classList.add("hidden");
                }, 100);
            } else {
                setTimeout(function () {
                    notification.classList.remove("translate-x-full");
                    notification.classList.add("translate-x-0");
                }, 100);
                checdiv.classList.remove("hidden");
            }
        },
    },
};
</script>
