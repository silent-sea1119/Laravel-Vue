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
              Title:
            </label>
            <input
              v-model="form.title"
              type="text"
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
              placeholder="Title"
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
              Company:
            </label>
            <input
              type="text"
              v-model="form.company"
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
              placeholder="Company"
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
              Location:
            </label>
            <input
              type="text"
              v-model="form.location"
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
              placeholder="Location"
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
              >Experience:
            </label>
            <input
              class="
                w-full
                py-2
                border-2
                rounded-md
                px-3
                text-gray-400
                focus:border-purple-600
                focus-within:border-purple-600
                focus:text-black
              "
              v-model="form.experience"
              placeholder="Example: 1 year or 2 years etc"
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
              Postion:
            </label>
            <input
              class="
                w-full
                py-2
                border-2
                rounded-md
                px-3
                text-gray-400
                focus:border-purple-600
                focus-within:border-purple-600
                focus:text-black
              "
              v-model="form.position"
              placeholder="Example: Full Time, Part Time etc"
            />
          </div>
          <div class="col-span-1">
            <label
              for="tag"
              class="
                block
                text-sm
                font-medium
                leading-5
                text-gray-700
                dark:text-gray-200
              "
            >
              Skills:
            </label>
            <!-- tag input container -->
            <div class="col-span-1">
              <!-- Key event when , or enter is pressed -->
              <input
                type="text"
                v-model="tag"
                @keydown.enter="tagifyString()"
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
                placeholder="Example: C++, python, DevOps"
              />
              <!-- Tag list -->
              <ul class="flex flex-wrap">
                <div
                  v-for="t in form.tags"
                  :key="t.id"
                  class="
                    w-fit
                    flex
                    items-start
                    px-3
                    py-1
                    rounded-sm
                    bg-gray-200
                    text-gray-700
                    mr-2
                    my-2
                    text-sm
                    shadow-md
                  "
                >
                  <li class="w-fit text-center mt-1 mr-1 text-sm font-semibold">
                    {{ t }}
                  </li>
                  <i
                    class="fa fa-close cursor-pointer"
                    @click="removeTag(t)"
                  ></i>
                </div>
              </ul>
            </div>
          </div>
        </div>
        <div class="w-inherit my-4">
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
            Description:
          </label>

          <mavon-editor language="en" v-model="form.description" />
        </div>

        <hr class="mt-2 mb-2" v-if="errors" />
        <DisplayError :errors="errors" />
        <hr class="mt-2 mb-2" v-if="errors" />
        <ButtonView
          :disable="this.loading || this.form.title === ''"
          @handleOnClick="manageJob"
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
import Swal from 'sweetalert2'

export default {
  name: "ManageJobScreen",
  components: {
    LoaderView,
    ButtonView,
    DisplayError,

  },
  props: {
    role: {
      type: String,
      default: "alumni"
    },
  },
  data() {
    return {
      loading: false,
      tag: "",
      form: {
        title: "",
        company: "",
        location: "",
        description: "",
        experience: "",
        tags: [],
        position: "",
      },
      job: {},
      label: "Add Job",
      errors: null,
    };
  },
  mounted() {
    // get ID from url
    const id = this.$route.params.id;
    // If ID Present but job is empty then, fetch from server.
    if (id && !this.job.id) {
      this.label = "Edit Job";
      document.title = `${this.label} - App`;
      this.loading = true;
      this.$store.dispatch("Job/getJob", id).then(() => {
        const error = this.$store.getters["Job/error"];
        this.job = this.$store.getters["Job/job"];
        if (error != null || !this.job.id) {
          if (this.role === "alumni") {
            this.$router.push("/jobs/list");
          } else {
            this.$router.push("/admin/job/manage");
          }
        }
        this.form = this.job;
        this.loading = false;
      });
    }
  },
  methods: {
    manageJob() {
      if (this.label === "Add Job") {
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
        text: "Job Save Successfully",
        type: "success",
        confirmButtonText: "OK",
      });
      
      if (this.role === "alumni") {
        this.$router.push("/jobs/list");
      } else {
        this.$router.push("/admin/jobs");
      }
    },
    addOrUpdate(type = "add") {
      this.errors = null;
      this.loading = true;
      if (type === "add") {
        this.$store.dispatch("Job/postJob", this.form).then((r) => {
          this.errors = this.$store.getters["Job/error"];
          this.showMsg();
        });
      } else {
        this.$store.dispatch("Job/updateJob", {
          id: this.job.id,
          form: this.form
        }).then((r) => {
          this.errors = this.$store.getters["Job/error"];
          this.showMsg();
        });
      }
    },
    tagifyString() {
      if (this.tag.trim() === "") {
        return;
      } else {
        this.tag = this.tag.toLowerCase().split(","); // split string by comma
        this.tag = this.tag.map((t) => t.trim()); // remove white spaces
        this.form.tags = this.form.tags.concat(this.tag); // concat to form tags
        // Remove duplicates from this.form.tags
        this.form.tags = [...new Set(this.form.tags)];
        this.tag = ""; // reset tag
      }
     
    },

    removeTag(tag) {
      // Remove that tag from the given tags.
      // Remove only 1st that matched, if there are multiple matched it will remove only first one.
      this.form.tags.splice(tag, 1)
    }
  },
}
</script>
