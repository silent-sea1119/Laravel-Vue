<template>
  <span v-if="show" class="text-bold block text-center"> {{ message }} </span>
</template>

<script lang="js">
export default {
    name: "NoRecordScreen",
    props: {
        show: {
            type: Boolean,
            default: false
        },
        message: {
            type: String,
            default: "Sorry, No record found."
        }
    }
}
</script>
