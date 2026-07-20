<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import moment from 'moment/moment';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import Pagination from '@/components/ui/Pagination.vue';
import type { PostIndexResponse, PostTopic } from '@/types/post';
import posts from '@/wayfinder/routes/posts';
import { Input } from '@/components/ui/input';
import { ref } from 'vue';
import { watchDebounced } from '@vueuse/core';

const props = defineProps<{
    postsData: PostIndexResponse;
    topics: PostTopic[];
    selectedTopic?: PostTopic;
}>();

const search = ref('');

const page = usePage();

watchDebounced(
    search,
    (value) => {
        router.get(
            page.url,
            {
                page: 1,
                query: value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['postsData'],
            },
        );
    },
    {
        debounce: 650,
    },
);
</script>

<template>
    <section class="mx-auto mb-3 w-full space-y-4 px-4 py-6">
        <div class="space-y-1">
            <h1 class="text-center text-3xl font-bold tracking-tight">
                {{ props.selectedTopic ? props.selectedTopic.name : 'Posts' }}
            </h1>
            <p
                v-if="!props.selectedTopic"
                class="text-center text-muted-foreground"
            >
                List of available posts ({{ props.postsData.data.length }})
            </p>
            <p v-else class="text-center text-muted-foreground">
                {{ props.selectedTopic?.description }}
            </p>
            <!-- Search input -->
            <div class="mx-auto w-1/2">
                <Input
                    v-model="search"
                    type="text"
                    name="search"
                    id="search"
                    placeholder="Search posts..."
                />
            </div>
        </div>

        <div class="flex flex-col-reverse justify-between gap-3 md:flex-row">
            <div
                v-if="props.postsData.data.length"
                class="flex w-full flex-col gap-4"
            >
                <Card
                    v-for="post in props.postsData.data"
                    :key="post.id"
                    class="h-full border-border/80 transition-shadow hover:cursor-pointer hover:border-[#3D4368CC] hover:bg-[#3D4368] hover:shadow-md"
                    @click="router.visit(posts.show({ post: post.id }))"
                >
                    <CardHeader class="flex items-center justify-between">
                        <div class="flex flex-col gap-2">
                            <CardTitle
                                class="text-lg leading-6 whitespace-normal"
                            >
                                {{ post.title }}
                            </CardTitle>
                            <div class="text-sm text-muted-foreground">
                                <p>
                                    Created by: {{ post.user.name }}
                                    <span class="opacity-55">{{
                                        moment(post.created_at).fromNow()
                                    }}</span>
                                </p>
                            </div>
                        </div>
                        <Badge variant="default" class="hover:opacity-80">
                            {{ post.topic.name }}
                        </Badge>
                    </CardHeader>
                </Card>
            </div>

            <Card v-else class="w-full border-dashed">
                <CardContent class="py-8 text-center text-muted-foreground">
                    No posts to show.
                </CardContent>
            </Card>

            <div class="w-full md:w-1/4">
                <menu
                    class="sticky top-5 flex flex-wrap justify-center gap-2 md:justify-start"
                >
                    <li>
                        <Badge
                            variant="default"
                            class="hover:cursor-pointer hover:opacity-80"
                            @click="
                                router.visit(posts.index(), {
                                    data: {
                                        query: search,
                                    },
                                    preserveState: true,
                                    preserveScroll: true,
                                })
                            "
                        >
                            All
                        </Badge>
                    </li>
                    <li v-for="topic in props.topics" :key="topic.id">
                        <Badge
                            variant="default"
                            class="hover:cursor-pointer hover:opacity-80"
                            @click="
                                router.visit(posts.index({ topic: topic.slug }), {
                                    data: {
                                        query: search,
                                    },
                                    preserveState: true,
                                    preserveScroll: true,
                                })
                            "
                        >
                            {{ topic.name }}
                        </Badge>
                    </li>
                </menu>
            </div>
        </div>

        <Pagination :meta="props.postsData.meta" :only="['postsData']" />
    </section>
</template>

<style scoped></style>
