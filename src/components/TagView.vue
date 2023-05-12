<template>
    <div 
        class="flex py-1 px-2 items-center rounded-md bg-gray-200 text-gray-700 w-max mx-2"
    >
        <span class="text-sm font-semibold">{{ this.tagName }}</span>
    </div>
</template>

<script>

export default {
    name: "TagView",
    props: {
        tagName: {
            type: String,
            default: "No Skills required", 
        }
    },
}

</script>