<template>
  <div class="home">
    <main class="h-full overflow-y-auto">
      <div class="container px-6 mx-auto grid">
        <h2
          class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
          Job Details
        </h2>
        <LoaderView :loading="loading" />

        <div
          class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-2 mb-2"
          v-if="!loading"
        >
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Title
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.title }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Company
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.company }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Location
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.location }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Description
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              <Markdown :source="job.description" />
            </span>
          </div>
          <div class="col-span-1 mx-2" v-if="job.faculty">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Faculty
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.faculty.name || "N/A" }}
            </span>
          </div>
          <div class="col-span-1 mx-2" v-if="job.degree_program">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Department (Degree Program)
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.degree_program.name || "N/A" }}
            </span>
          </div>
          <div class="col-span-1 mx-2" v-if="true">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Experience
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.experience || "N/A" }}
            </span>
          </div>
          <div class="col-span-1 mx-2" v-if="true">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Hiring Position
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.position || "N/A" }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Posted
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.created_at }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Last modified
            </h2>
            <span class="text-gray-700 dark:text-gray-200">
              {{ job.updated_at }}
            </span>
          </div>
          <div class="col-span-1 mx-2">
            <h2 class="text-1xl font-semibold text-gray-700 dark:text-gray-200">
              Skills
            </h2>
            <span class="tracking-tight text-gray-700 my-2 flex items-center">
              <TagView
                class="mr-2"
                :tagName="skill"
                v-for="skill in jobSkills(job.skills)"
                :key="skill"
              />
            </span>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script lang="js">
import Markdown from 'vue3-markdown-it';
import LoaderView from "@/components/LoaderView.vue";
import TagView from "@/components/TagView.vue"
import {
    jobSkills
} from '@/utils'

export default {
    name: "ViewJobScreen",
    components: {
        LoaderView,
        Markdown,
        TagView
    },
    props: {
        role: {
            type: String,
            default: "alumni"
        },
        data: {
            type: Object,
            default: null,
        }
    },
    data() {
        return {
            job: {},
            loading: false,
        };
    },
    mounted() {
        if (this.data) {
            this.job = this.data;
            return;
        }
        // get ID from url
        const id = this.$route.params.id;
        // If ID Present but user is empty then, fetch from server.
        if (id && !this.job.id) {
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
                } else {
                    this.skills = JSON.parse(this.job.skills)
                }
                this.loading = false;
            });
        }
    },
    methods: {
        jobSkills,
    }
}
</script>
