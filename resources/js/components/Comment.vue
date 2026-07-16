<script setup lang="ts">
import moment from 'moment';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { ThumbsDown, ThumbsUp } from 'lucide-vue-next';
import { useReaction } from '@/composables/useReaction';

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

const emit = defineEmits(['delete', 'update']);

const { like, unlike, dislike, undislike } = useReaction();

const toggleLike = () => {
    if (!props.comment.isLiked) {
        like('comment', props.comment.id);
    } else {
        unlike('comment', props.comment.id);
    }
};
const toggleDislike = () => {
    if (!props.comment.isDisliked) {
        dislike('comment', props.comment.id);
    } else {
        undislike('comment', props.comment.id);
    }
};
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
            <div
                class="prose prose-sm mt-1 max-w-none dark:prose-invert"
                v-html="props.comment.html"
            />
            <div class="mt-2 flex justify-end gap-2">
                <Button
                    v-if="props.comment.can?.update"
                    class="hover:cursor-pointer"
                    variant="secondary"
                    @click="emit('update', props.comment.id)"
                >
                    Edit
                </Button>
                <Button
                    v-if="props.comment.can?.delete"
                    class="bg-red-400 text-white hover:cursor-pointer hover:bg-red-500"
                    @click="emit('delete', props.comment.id)"
                >
                    Delete
                </Button>

                <div
                    v-if="
                        !props.comment.can?.delete && !props.comment.can?.update
                    "
                    class="flex items-center gap-2"
                >
                    <div
                        class="flex flex-col content-center items-center gap-2"
                    >
                        <ThumbsUp
                            :size="24"
                            color="#6272A4"
                            :fill="comment.isLiked ? '#6272A4' : '#6272A499'"
                            class="hover:cursor-pointer"
                            @click="toggleLike"
                        />
                        <span class="text-sm">
                            {{ props.comment.likes_count }}
                        </span>
                    </div>
                    <div
                        class="flex flex-col content-center items-center gap-2"
                    >
                        <ThumbsDown
                            :size="22"
                            color="#FF5555"
                            :fill="comment.isDisliked ? '#FF5555' : '#FF555599'"
                            class="hover:cursor-pointer"
                            @click="toggleDislike"
                        />
                        <span class="text-sm">
                            {{ props.comment.dislikes_count ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>

<style scoped></style>
