<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { useVModel } from "@vueuse/core"
import { cn } from "@/lib/utils"

// 1. Definição correta de Props com TypeScript
interface Props {
    defaultValue?: string | number
    modelValue: string | number
    rows?: number
    class?: HTMLAttributes["class"]
}

// 2. Aplicação de valores default
const props = withDefaults(defineProps<Props>(), {
    rows: 3,
})

const emits = defineEmits<{
    (e: "update:modelValue", payload: string | number): void
}>()

const modelValue = useVModel(props, "modelValue", emits, {
    passive: true,
    defaultValue: props.defaultValue,
})
</script>

<template>
  <textarea
      v-model="modelValue"
      :rows="rows"
      data-slot="input"
      :class="cn(
      'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex w-full min-w-0 rounded-md border bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
      'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      props.class,
    )"
  />
</template>
