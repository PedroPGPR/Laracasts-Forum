<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { isInProduction } from '@/lib/utils';
import type { PostTopic } from '@/types/post';
import posts from '@/wayfinder/routes/posts';

const props = defineProps<{
    topics: PostTopic[];
}>();

const form = useForm({
    title: '',
    topic_id: 0,
    body: '',
});

const selectedTopicId = computed({
    get: () => (form.topic_id ? form.topic_id : 0),
    set: (value: string|number) => {
        form.topic_id = Number(value);
    },
});

const createPost = () => {
    form.post(posts.store.url(), {
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

                <div class="mb-4">
                    <label for="topic_id" class="px-1">Topic: </label>
                    <Select v-model="selectedTopicId">
                        <SelectTrigger id="topic_id" class="mt-1 w-full">
                            <SelectValue placeholder="Select a topic" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="topic in props.topics"
                                :key="topic.id"
                                :value="topic.id"
                            >
                                {{ topic.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError
                        :message="form.errors.topic_id"
                        class="mt-1 text-sm"
                    />
                </div>

                <div class="mb-4 flex flex-col">
                    <label for="body" class="px-1 mb-1">Body: </label>
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
