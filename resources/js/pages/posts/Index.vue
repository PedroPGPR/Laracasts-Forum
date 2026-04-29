<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import Pagination from '@/components/ui/Pagination.vue';
import type { PostIndexResponse } from '@/types/post';

defineProps<{
    posts: PostIndexResponse;
}>();

const showPost = (id: number) => {
    router.visit(`/posts/${id}`);
};
</script>

<template>
    <section class="mx-auto mb-3 max-w-6xl space-y-6 px-4 py-6">
        <div class="space-y-1 text-center">
            <h1 class="text-3xl font-bold tracking-tight">Posts</h1>
            <p class="text-muted-foreground">
                Lista de posts disponíveis ({{ posts.data.length }})
            </p>
        </div>

        <div
            v-if="posts.data.length"
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
        >
            <Card
                v-for="post in posts.data"
                :key="post.id"
                class="h-full border-border/80 transition-shadow hover:cursor-pointer hover:border-[#3D4368CC] hover:bg-[#3D4368] hover:shadow-md"
                @click="showPost(post.id)"
            >
                <CardHeader>
                    <CardTitle class="text-lg leading-6">
                        {{ post.title }}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        class="max-h-32 overflow-hidden text-sm text-muted-foreground"
                    >
                        {{ post.body }}
                    </p>
                </CardContent>
            </Card>
        </div>

        <Card v-else class="border-dashed">
            <CardContent class="py-8 text-center text-muted-foreground">
                Ainda nao existem posts para mostrar.
            </CardContent>
        </Card>

        <Pagination :meta="posts.meta" :only="['posts']" />
    </section>
</template>

<style scoped></style>
