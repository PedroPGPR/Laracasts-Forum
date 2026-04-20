<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { useSidebar } from '@/components/ui/sidebar';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();

const { state } = useSidebar();
</script>

<template>
    <template v-if="state === 'collapsed'">
        <img
            src="/assets/symbol-negative.png"
            class="hidden bg-transparent dark:block"
            alt="Logo"
        />

        <img
            src="/assets/symbol-analog.png"
            class="block dark:hidden"
            alt="Logo"
        />
    </template>
    <template v-else>
        <img
            src="/assets/framerate-negative.svg"
            class="hidden bg-transparent dark:block"
            alt="Logo"
        />

        <img
            src="/assets/framerate-analog.svg"
            class="block dark:hidden"
            alt="Logo"
        />
    </template>
</template>
