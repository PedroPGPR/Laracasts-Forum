<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import moment from 'moment/moment';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import Pagination from '@/components/ui/Pagination.vue';
import type { PostIndexResponse, PostTopic } from '@/types/post';
import posts from '@/wayfinder/routes/posts';

const props = defineProps<{
    postsData: PostIndexResponse;
    topics: PostTopic[];
    selectedTopic?: PostTopic;
}>();
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
        </div>

        <div class="flex flex-col-reverse md:flex-row justify-between gap-3">
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

            <Card v-else class="border-dashed">
                <CardContent class="py-8 text-center text-muted-foreground">
                    Ainda nao existem posts para mostrar.
                </CardContent>
            </Card>

            <div class="w-full md:w-1/4">
                <menu class="flex flex-wrap justify-center md:justify-start gap-2 sticky top-5">
                    <li>
                        <Badge
                            variant="default"
                            class="hover:cursor-pointer hover:opacity-80"
                            @click="router.visit(posts.index())"
                        >
                            All
                        </Badge>
                    </li>
                    <li v-for="topic in props.topics" :key="topic.id">
                        <Badge
                            variant="default"
                            class="hover:cursor-pointer hover:opacity-80"
                            @click="
                                router.visit(posts.index({ topic: topic.slug }))
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
