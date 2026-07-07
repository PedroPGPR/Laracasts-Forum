<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import moment from 'moment/moment';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Pagination from '@/components/ui/Pagination.vue';
import type { PostIndexResponse } from '@/types/post';

defineProps<{
    posts: PostIndexResponse;
}>();
</script>

<template>
    <section class="mx-auto mb-3 w-full space-y-6 px-4 py-6">
        <div class="space-y-1 text-center">
            <h1 class="text-3xl font-bold tracking-tight">Posts</h1>
            <p class="text-muted-foreground">
                Lista de posts disponíveis ({{ posts.data.length }})
            </p>
        </div>

        <div v-if="posts.data.length" class="flex flex-col gap-4">
            <Card
                v-for="post in posts.data"
                :key="post.id"
                class="h-full border-border/80 transition-shadow hover:cursor-pointer hover:border-[#3D4368CC] hover:bg-[#3D4368] hover:shadow-md"
                @click="router.visit(post.routes.show)"
            >
                <CardHeader class="flex justify-between items-center">
                    <div class="flex flex-col gap-2">
                        <CardTitle class="text-lg leading-6 whitespace-normal">
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

        <Pagination :meta="posts.meta" :only="['posts']" />
    </section>
</template>

<style scoped></style>
