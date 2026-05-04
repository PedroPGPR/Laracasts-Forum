<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import moment from 'moment';
import Comment from '@/components/Comment.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import Pagination from '@/components/ui/Pagination.vue';
import { Textarea } from '@/components/ui/textarea';

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
const addComment = () => {
    form.post(
        route('posts.comments.store', props.post.id), {
            preserveScroll: true,
            onSuccess: () => form.reset('body'),
        }
    );
};

const deleteComment = (id: number) => {
    router.delete(
        route('posts.comments.destroy', { post: props.post.id, comment: id, page: props.comments.meta.current_page }),
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <div class="my-10 flex flex-col gap-10">
        <Card class="mx-auto w-2/3 p-5">
            <h1 class="font-bold">{{ props.post.title }}</h1>
            <div>
                <div class="flex justify-between">
                    <div>Created by: {{ props.post.user.name }}<br /></div>
                    <div class="opacity-55">
                        {{ moment(props.post.created_at).fromNow() }}
                    </div>
                </div>
                <p class="mt-2 text-justify">
                    {{ props.post.body }}
                </p>
            </div>
        </Card>

        <Card class="mx-10 p-5">
            <h2 class="font-bold">Comments</h2>
            <form v-if="$page.props.auth.user" @submit.prevent="addComment">
                <div class="mb-4">
                    <Textarea
                        v-model="form.body"
                        :rows="3"
                        placeholder="Add a comment..."
                    />
                    <InputError
                        :message="form.errors.body"
                        class="mt-1 text-sm"
                    />
                </div>

                <Button
                    class="float-end hover:cursor-pointer"
                    type="submit"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Adding Comment' : 'Add Comment' }}
                </Button>
            </form>
            <template v-for="comment in props.comments.data" :key="comment.id">
                <Comment
                    :comment="comment"
                    :post-id="props.post.id"
                    @delete="deleteComment"
                />
            </template>

            <Pagination :meta="comments.meta" :only="['comments']" />
        </Card>
    </div>
</template>

<style scoped></style>
