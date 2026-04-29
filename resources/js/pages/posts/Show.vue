<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import moment from 'moment';
import { ref } from 'vue';
import { store } from '@/actions/App/Http/Controllers/CommentController';
import Comment from '@/components/Comment.vue';
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
        type: Array,
        required: true,
    },
});

const body = ref('');
const addComment = () => {
    store({ post: props.post.id });
}
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
                <Textarea
                    v-model="body"
                    :rows="3"
                    placeholder="Add a comment..."
                    class="mb-4"
                />

                <Button class="float-end hover:cursor-pointer" type="submit">Add Comment</Button>
            </form>
            <template v-for="comment in props.comments.data" :key="comment.id">
                <Comment :comment="comment" />
            </template>

            <Pagination :meta="comments.meta" :only="['comments']" />
        </Card>
    </div>
</template>

<style scoped></style>
