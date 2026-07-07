<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { isInProduction } from '@/lib/utils';

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

const autofill = async () => {
    if (isInProduction()) {
        return;
    }

    const response = await fetch('/local/post-content');
    const data = await response.json();

    form.title = data.title;
    form.body = data.body;
};
</script>

<template>
    <div class="my-10 flex flex-col gap-10">
        <h1 class="text-center text-2xl font-bold">Create a Post</h1>

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
                    <MarkdownEditor v-model="form.body">
                        <template
                            #toolbar="{
                                toolbarButtonBase,
                                toolbarButtonActive,
                                toolbarButtonInactive,
                                isHeadingActive,
                            }"
                        >
                            <li v-if="!isInProduction()">
                                <button
                                    type="button"
                                    class="hover:cursor-pointer"
                                    :class="[
                                        toolbarButtonBase,
                                        isHeadingActive(4)
                                            ? toolbarButtonActive
                                            : toolbarButtonInactive,
                                    ]"
                                    aria-label="Import Markdown"
                                    :aria-pressed="isHeadingActive(4)"
                                    @click="autofill"
                                >
                                    <i
                                        class="ri-article-line text-base leading-none"
                                    ></i>
                                </button>
                            </li>
                        </template>
                    </MarkdownEditor>
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
