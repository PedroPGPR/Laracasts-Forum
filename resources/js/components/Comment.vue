<script setup lang="ts">
import moment from 'moment';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
    postId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['delete']);
</script>

<template>
    <Card class="flex flex-col gap-4 bg-accent-foreground">
        <div class="px-5">
            <div class="flex justify-start text-sm">
                <div class="opacity-55">
                    {{ moment(props.comment.updated_at).fromNow() }} by
                    {{ props.comment.user.name }}
                </div>
                <!-- v-if="comment.user.name === $page.props.auth.user.id"-->
            </div>
            <p class="mt-1 text-justify">
                {{ props.comment.body }}
            </p>
            <Button
                v-if="props.comment.can?.delete"
                class="bg-red-400 text-white hover:cursor-pointer hover:bg-red-500 float-end mt-2"
                @click="emit('delete', props.comment.id)"
            >
                Delete
            </Button>
        </div>
    </Card>
</template>

<style scoped></style>
