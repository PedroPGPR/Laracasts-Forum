<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';

const form = useForm({
    title: '',
    body: '',
});

const createPost = () => {
    form.post(route('posts.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div class="my-10 flex flex-col gap-10">
        <h1 class="font-bold text-center text-2xl">Create a Post</h1>

        <Card class="mx-10 p-5">
            <form @submit.prevent="createPost">
                <div class="mb-4">
                    <label for="title" class="px-1">Title</label>
                    <Input
                        id="title"
                        type="text"
                        class="mt-1"
                        v-model="form.title"
                    />
                    <InputError
                        :message="form.errors.title"
                        class="mt-1 text-sm"
                    />
                </div>

                <div class="mb-4 flex flex-col">
                    <label for="body" class="px-1">Body</label>
                    <MarkdownEditor v-model="form.body" />
                    <Textarea
                        id="body"
                        v-model="form.body"
                        class="mt-1"
                        :rows="10"

                    ></Textarea>
                    <InputError
                        :message="form.errors.body"
                        class="mt-1 text-sm"
                    />
                </div>

                <div class="flex justify-end">
                    <Button type="submit" :disabled="form.processing">
                        Create Post
                    </Button>
                </div>
            </form>
        </Card>
    </div>
</template>

<style scoped></style>
