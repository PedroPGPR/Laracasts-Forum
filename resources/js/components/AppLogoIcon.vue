<script setup lang="ts">
import type { HTMLAttributes } from 'vue';


defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
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
