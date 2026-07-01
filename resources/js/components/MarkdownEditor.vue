<script setup lang="ts">
import { StarterKit } from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { Markdown } from 'tiptap-markdown';
import { watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    extensions: [
        StarterKit,
        Markdown
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl max-w-none py-1.5 px-3 file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex w-full min-w-0 rounded-md border bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive'
        }
    },
    onUpdate: () => emit('update:modelValue', editor.value?.storage.markdown.getMarkdown())
});

watch(() => props.modelValue, (value) => {
    if(value === editor.value?.storage.markdown.getMarkdown()) {
return;
}

    editor.value?.commands.setContent(value);
}, { immediate: true });
</script>

<template>
    <div>
        <EditorContent :editor="editor" />
    </div>
</template>

<style scoped></style>
