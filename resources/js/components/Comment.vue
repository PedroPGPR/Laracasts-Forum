<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import moment from 'moment';
import { computed } from 'vue';
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

const deleteComment = (id: number) => {
    router.delete(
        route('posts.comments.destroy', { post: props.postId, comment: id }),
        {
            preserveScroll: true
        }
    );
};

const canDelete = computed(() => {
    const authUser = usePage().props.auth.user;

    return authUser && (authUser.id === props.comment.user.id);
});
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
                v-if="canDelete"
                class="bg-red-400 text-white hover:cursor-pointer hover:bg-red-500 float-end mt-2"
                @click="deleteComment(props.comment.id)"
            >
                Delete
            </Button>
        </div>
    </Card>
</template>

<style scoped></style>
