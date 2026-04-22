<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'

type Post = {
    id: number
    title: string
    body: string
}

defineProps<{
    posts: Post[]
}>()
</script>

<template>
    <section class="mx-auto max-w-6xl space-y-6 px-4 py-6">
        <div class="space-y-1 text-center">
            <h1 class="text-3xl font-bold tracking-tight">Posts</h1>
            <p class="text-muted-foreground">
                Lista de posts disponíveis ({{ posts.length }})
            </p>
        </div>

        <div
            v-if="posts.length"
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
        >
            <Card
                v-for="post in posts"
                :key="post.id"
                class="h-full border-border/80 transition-shadow hover:shadow-md "
            >
                <CardHeader>
                    <CardTitle class="text-lg leading-6">{{ post.title }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <p class="max-h-32 overflow-y-auto text-sm text-muted-foreground">
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
    </section>
</template>

<style scoped></style>
