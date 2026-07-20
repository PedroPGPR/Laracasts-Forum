<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ThumbsUp, ThumbsDown } from 'lucide-vue-next';
import moment from 'moment';
import { computed, ref } from 'vue';
import Comment from '@/components/Comment.vue';
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import Pagination from '@/components/ui/Pagination.vue';
import { useNotification } from '@/composables/useNotification';
import { useReaction } from '@/composables/useReaction';
import commentsRoutes from '@/wayfinder/routes/posts/comments';

const { confirmNotification } = useNotification();
const { like, unlike, dislike, undislike } = useReaction();

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    comments: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    body: '',
});

const commentIDBeingEdited = ref(0);
const commentEditorRef = ref();
const commentBeingEdited = computed(() => {
    return props.comments.data.find(
        (comment: { id: number }) => comment.id === commentIDBeingEdited.value,
    );
});
const editComment = (commentID: number) => {
    commentIDBeingEdited.value = commentID;
    form.body = commentBeingEdited.value?.body;
    commentEditorRef.value?.focus();
};
const cancelUpdate = () => {
    commentIDBeingEdited.value = 0;
    form.reset('body');
};

const addComment = () => {
    form.post(commentsRoutes.store.url(props.post.id), {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
};

const updateComment = () => {
    form.put(
        commentsRoutes.update.url(
            { post: props.post.id, comment: commentIDBeingEdited.value },
            { query: { page: props.comments.meta.current_page } },
        ),
        {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('body');
                commentIDBeingEdited.value = 0;
            },
        },
    );
};

const deleteComment = (id: number) => {
    confirmNotification({
        type: 'warning',
        title: 'Warning',
        message:
            'You are about to delete this comment. You sure you want to delete it?',
        confirmAction: async () => {
            router.delete(
                commentsRoutes.destroy.url(
                    { post: props.post.id, comment: id },
                    {
                        query: {
                            page:
                                props.comments.data.length > 1
                                    ? props.comments.meta.current_page
                                    : Math.max(
                                          props.comments.meta.current_page - 1,
                                          1,
                                      ),
                        },
                    },
                ),
                {
                    preserveScroll: true,
                },
            );
        },
        cancelAction: false,
    });
};

const toggleLike = () => {
    if (!props.post.isLiked) {
        like('post', props.post.id);
    } else {
        unlike('post', props.post.id);
    }
};
const toggleDislike = () => {
    if (!props.post.isDisliked) {
        dislike('post', props.post.id);
    } else {
        undislike('post', props.post.id);
    }
};
</script>

<template>
    <Head>
        <link rel="canonical" :href="props.post.routes.show" />
    </Head>

    <div class="my-10 flex flex-col gap-10">
        <Card class="mx-auto w-4/5 p-5">
            <h1 class="font-bold">{{ props.post.title }}</h1>
            <div>
                <div class="flex flex-col content-center justify-between">
                    <div class="flex justify-between">
                        <div>Created by: {{ props.post.user.name }}</div>
                        <Badge variant="default" class="hover:opacity-80">
                            {{ props.post.topic.name }}
                        </Badge>
                    </div>
                    <div class="opacity-55">
                        {{ moment(props.post.created_at).fromNow() }}
                    </div>
                </div>
                <article
                    v-html="props.post.html"
                    class="prose prose-sm mt-2 max-w-none dark:prose-invert"
                ></article>
                <hr />
                <div class="mt-3 flex justify-end">
                    <div class="flex items-center gap-2">
                        <div
                            class="flex flex-col content-center items-center gap-2"
                        >
                            <ThumbsUp
                                :size="24"
                                color="#6272A4"
                                :fill="props.post.isLiked ? '#6272A4' : '#6272A499'"
                                class="hover:cursor-pointer"
                                @click="toggleLike"
                            />
                            <span class="text-sm">
                                {{ props.post.likes_count ?? 0 }}
                            </span>
                        </div>
                        <div
                            class="flex flex-col content-center items-center gap-2"
                        >
                            <ThumbsDown
                                :size="22"
                                color="#FF5555"
                                :fill="props.post.isDisliked ? '#FF5555' : '#FF555599'"
                                class="hover:cursor-pointer"
                                @click="toggleDislike"
                            />
                            <span class="text-sm">
                                {{ props.post.dislikes_count ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </Card>

        <Card class="mx-10 p-5">
            <h2 class="font-bold">Comments</h2>
            <form
                v-if="$page.props.auth.user"
                @submit.prevent="
                    () =>
                        commentIDBeingEdited ? updateComment() : addComment()
                "
            >
                <div class="mb-4">
                    <MarkdownEditor
                        v-model="form.body"
                        ref="commentEditorRef"
                        :rows="3"
                        placeholder="Add a comment..."
                        editorClass="min-h-[150px]"
                    />
                    <InputError
                        :message="form.errors.body"
                        class="mt-1 text-sm"
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <Button
                        v-if="commentIDBeingEdited"
                        class="hover:cursor-pointer"
                        variant="secondary"
                        @click="cancelUpdate"
                    >
                        Cancel
                    </Button>
                    <Button
                        class="hover:cursor-pointer"
                        :disabled="form.processing"
                        type="submit"
                    >
                        {{
                            commentIDBeingEdited
                                ? form.processing
                                    ? 'Updating Comment'
                                    : 'Update Comment'
                                : form.processing
                                  ? 'Adding Comment'
                                  : 'Add Comment'
                        }}
                    </Button>
                </div>
            </form>
            <template v-for="comment in props.comments.data" :key="comment.id">
                <Comment
                    :comment="comment"
                    :post-id="props.post.id"
                    @delete="deleteComment"
                    @update="editComment"
                />
            </template>

            <Pagination :meta="comments.meta" :only="['comments']" />
        </Card>
    </div>
</template>

<style scoped></style>
