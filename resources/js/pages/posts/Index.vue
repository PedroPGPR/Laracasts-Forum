<script setup lang="ts">

defineProps({
    posts: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <h1 class="text-center font-bold">POSTS</h1>
    <h5 class="text-center font-bold">List of Posts</h5>

    <table class="table table-striped border-2 border-white rounded-lg w-full">
        <thead>
            <tr class="border border-white">
                <th class="border border-white text-center">ID</th>
                <th class="border border-white text-center">Title</th>
                <th class="border border-white text-center">Content</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border border-white" v-for="post in posts" :key="post.id">
                <td class="border border-white text-center p-2">{{ post.id }}</td>
                <td class="border border-white text-center p-2">{{ post.title }}</td>
                <td class="border border-white text-justify p-2">{{ post.body }}</td>
            </tr>
        </tbody>
    </table>
</template>

<style scoped></style>
