<script setup lang="ts">
import moment from 'moment';
import { Card } from '@/components/ui/card';

defineProps({
    post: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="my-10">
        <Card class="mx-auto w-2/3 p-5">
            <h1 class="font-bold">{{ post.title }}</h1>
            <div>
                <div class="flex justify-between">
                    <div>Created by: {{ post.user.name }}<br /></div>
                    <div class="opacity-55">
                        {{ moment(post.created_at).fromNow() }}
                    </div>
                </div>
                <p class="mt-2 text-justify">
                    {{ post.body }}
                </p>
            </div>
        </Card>
    </div>
</template>

<style scoped></style>
