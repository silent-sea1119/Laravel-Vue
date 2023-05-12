<template>
  <div>
    <TabLayout
      class="bg-white shadow-sm border-t-4 border-gray-400 mt-0 shadow-md"
    >
      <TabLink
        label="About"
        icon="fa fa-user"
        id="about"
        :active="activeTab === 'about-tab'"
        @click="tabSwitch('about-tab', this.activeTab)"
      />
      <TabLink
        label="Education"
        icon="fa fa-graduation-cap"
        id="education"
        :active="activeTab === 'education-tab'"
        @click="tabSwitch('education-tab', this.activeTab)"
      />
      <TabLink
        label="Experience"
        icon="fa fa-briefcase"
        id="experience"
        :active="activeTab === 'experience-tab'"
        @click="tabSwitch('experience-tab', this.activeTab)"
      />
    </TabLayout>

    <ProfileAbout
      id="about-tab"
      class=""
      :user="user"
      :currentUser="currentUser"
    />

    <ProfileEducation
      id="education-tab"
      class="hidden"
      :user="user"
      :currentUser="currentUser"
    />

    <ProfileExperience
      id="experience-tab"
      class="hidden"
      :user="user"
      :currentUser="currentUser"
    />
    <ButtonView
      v-if="user.id === currentUser.id && activeTab !== 'about-tab'"
      class="float-right"
      text="Import From LinkedIn"
      @click="importLinkedin"
    />
  </div>
</template>

<script lang="js">
import TabLayout from '@/components/Tab/TabLayout.vue'
import TabLink from '@/components/Tab/TabLink.vue'
import ProfileAbout from '@/views/User/Profile/ProfileAbout.vue'
import ProfileEducation from '@/views/User/Profile/ProfileEducation.vue'
import ProfileExperience from '@/views/User/Profile/ProfileExperience.vue'
import ButtonView from '@/components/ButtonView.vue'
import Swal from 'sweetalert2'

import {
  tabViewHelper
} from "@/utils";

export default {
  name: "ProfileLayout",
  components: {
    TabLayout,
    TabLink,
    ProfileAbout,
    ProfileEducation,
    ProfileExperience,
    ButtonView,
  },
  mounted() {
    const query = this.$route.query.tab;
    if (query && this.tabs.includes(query)) {
      this.tabSwitch(query + "-tab", this.activeTab);
    }
  },
  data() {
    return {
      activeTab: 'about-tab',
      tabs: [
        'about',
        'education',
        'experience',
      ],
      education: [],
      linkedin: "",
      data: null,
    }
  },
  props: {
    user: {
      type: Object,
      required: true,
    },
    currentUser: {
      type: Object,
      required: true,
    },
  },
  tabViewHelper,
  methods: {
    tabSwitch(show, hide) {
      if (show === this.activeTab)
        return
      this.activeTab = show;
      tabViewHelper(show, hide);
      this.$router.push({
        query: {
          tab: show.split("-")[0]
        }
      });
    },
    importLinkedin() {
      if (this.linkedin === "") {
        Swal.fire({
          title: "Invalid Operaion!",
          text: "You have not linked your LinkedIn account yet.",
          type: "warning",
          confirmButtonText: "OK",
        });
      } else {
        // this.$store.dispatch("Linkedin/getLinkedin").then((r) => {
        //   console.log("Done");
        //   this.data = r;
        // });
        
        // call the getLinkedin action from the store
        this.$store.dispatch("Linkedin/getLinkedin", this.linkedin).then((r) => {
          // console.log("Done");
          this.data = r;
        });
    }
  }
  }
}
</script>
