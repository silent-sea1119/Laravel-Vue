<template>
  <nav
    class="
      md:flex
      sm:block
      px-5
      py-3
      text-gray-700
      border border-gray-200
      rounded-lg
      bg-white
      m-3
      dark:bg-gray-800 dark:border-gray-700
      shadow-sm shadow-gray-200
    "
    aria-label="Breadcrumb"
  >
    <ol class="md:inline-flex md:items-center sm:block space-x-1 md:space-x-3">
      <li
        class="md:inline-flex md:items-center sm:block"
        v-for="(breadcrumb, index) in breadcrumbs"
        :key="index"
      >
        <span
          @click="navigateTo(index)"
          class="
            inline-flex
            items-center
            text-sm
            font-medium
            hover:text-blue-500
            dark:text-gray-400 dark:hover:text-white
          "
          :class="activeClass(breadcrumb.link)"
        >
          <svg
            v-if="index !== 0"
            class="w-6 h-6 text-gray-400"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"
            ></path>
          </svg>

          <i class="fa fa-home mr-2" v-if="breadcrumb.name === 'Dashboard'"></i>

          {{ breadcrumb.name }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script lang="js">
export default {
    name: "BreadCrumb",
    data() {
        return {
            breadcrumbs: [],
        }
    },
    watch: {
        $route (to, from) {
            this.updateBreadcrumbs()
        }
    },
    mounted() {
        this.updateBreadcrumbs()

        // If the event trigger.
        this.emitter.on('emit-title', this.getTitle)
    },
    methods: {
        updateBreadcrumbs() {
          this.breadcrumbs = this.$route.meta.breadcrumb
          const params = this.$route.params
          
          // If value exists in param.
          if (params) {
            // get first param value.
            const firstParam = Object.values(params)[0]
            
            // Update the breadcrumb link if exists with :id.
            this.breadcrumbs?.forEach((breadcrumb) => {
                breadcrumb.link = breadcrumb.link?.replace(
                  ':id',
                  firstParam
                )
            })
          }
        },
        navigateTo(route) {
            // Navigate if the link is present.
            // If the current item is not the last item.
            if (this.breadcrumbs[route].link) {
                this.$router.push(this.breadcrumbs[route].link)
            }
        },
        getTitle(data) {
            // Update the breadcrumb name based on index.
            // If index is not given update last item.
            const index = data.index ?? this.breadcrumbs.length - 1
            const value = data.value ?? data
            this.breadcrumbs[index].name = value
        },
      activeClass(link) {
        if (link) return 'text-gray-500 cursor-pointer'
        return 'text-blue-500'
      }
    },
};
</script>
