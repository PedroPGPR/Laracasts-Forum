<script setup>
import { Link } from "@inertiajs/vue3"
import { computed } from 'vue';

const props = defineProps({
    meta: {
        type: Object,
        required: true
    }
});

const previousURL = computed(() => props.meta.links[0].url);
const nextURL = computed(() => {
    const links = props.meta.links;
    return links[links.length - 1].url;
});
</script>

<template>
    <div class="flex items-center justify-between border-t border-white/10 px-4 py-3 sm:px-6">
        <!-- Mobile -->
        <div class="flex flex-1 justify-end gap-2 md:hidden">
            <template v-if="previousURL">
                <Link
                    :href="previousURL"
                    class="relative inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10"
                >
                    Previous
                </Link>
            </template>
            <template v-if="nextURL">
                <Link
                    :href="nextURL"
                    class="relative ml-3 inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10"
                >
                    Next
                </Link>
            </template>
        </div>
        <!-- Desktop -->
        <div class="hidden md:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-300">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.total }}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md" aria-label="Pagination">
                    <template v-for="(link, key) in meta.links" :key="key">
                        <template v-if="link.url">
                            <Link
                                :href="link.url"
                                class="relative inline-flex items-center first-of-type:rounded-l-md last-of-type:rounded-r-md px-3 py-2"
                                :class="{
                                    'z-10 text-white focus-visible:outline-2 focus-visible:outline-offset-2 bg-indigo-500 focus-visible:outline-indigo-500': link.active,
                                    'inset-ring focus:outline-offset-0 text-gray-200 inset-ring-gray-700 hover:bg-white/5': !link.active
                                }"
                            >
                                <span v-html="link.label" />
                            </Link>
                        </template>
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>
