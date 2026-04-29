<script setup lang="ts">
import moment from 'moment';
import { Card } from '@/components/ui/card';

defineProps({
    comment: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Card class="flex flex-col gap-4 bg-accent-foreground">
        <div class="px-5">
            <div class="text-sm flex justify-start">
                <div class="opacity-55">
                    {{ moment(comment.updated_at).fromNow() }} by
                    {{ comment.user.name }}
                </div>
            </div>
            <p class="mt-1 text-justify">
                {{ comment.body }}
            </p>
        </div>
    </Card>
</template>

<style scoped></style>
