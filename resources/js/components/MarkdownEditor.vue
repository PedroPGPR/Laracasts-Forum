<script setup lang="ts">
import { Placeholder } from '@tiptap/extension-placeholder';
import { StarterKit } from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { Markdown } from 'tiptap-markdown';
import { watch } from 'vue';
import 'remixicon/fonts/remixicon.css';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    editorClass: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Write something...',
    },
});

const emit = defineEmits(['update:modelValue']);

const getMarkdownValue = () => {
    const storage = editor.value?.storage as
        | {
              markdown?: {
                  getMarkdown: () => string;
              };
          }
        | undefined;

    return storage?.markdown?.getMarkdown() ?? '';
};

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
            code: false,
            codeBlock: false,
        }),
        Markdown,
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],
    editorProps: {
        attributes: {
            class: `prose prose-sm sm:prose lg:prose-lg xl:prose-2xl dark:prose-invert w-full max-w-full !max-w-none min-h-48 rounded-b-xl border border-input bg-transparent px-3 py-2 text-sm prose-p:text-sm prose-li:text-sm text-foreground shadow-xs transition-[color,box-shadow] outline-none file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 prose-headings:text-foreground prose-p:text-foreground prose-strong:text-foreground prose-em:text-foreground prose-blockquote:text-foreground prose-li:text-foreground prose-a:text-primary focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive ${props.editorClass}`,
        },
    },
    onUpdate: () => emit('update:modelValue', getMarkdownValue()),
});

const toolbarButtonBase =
    'inline-flex size-9 items-center justify-center rounded-md border border-transparent text-sm transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:pointer-events-none disabled:opacity-50';
const toolbarButtonActive = 'bg-primary text-primary-foreground shadow-sm';
const toolbarButtonInactive =
    'text-muted-foreground hover:bg-accent hover:text-accent-foreground';
const toolbarClass =
    'inline-flex items-center gap-1 rounded-t-xl border border-b-0 border-border bg-card/95 p-1 shadow-sm backdrop-blur';

const isHeadingActive = (level: 1 | 2 | 3 | 4) =>
    editor.value?.isActive('heading', { level }) ?? false;

watch(
    () => props.modelValue,
    (value) => {
        if (value === getMarkdownValue()) {
            return;
        }

        editor.value?.commands.setContent(value);
    },
    { immediate: true },
);

const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        editor.value?.chain().focus().unsetLink().run();

        return;
    }

    const href = prompt('What is the link');

    if (!href) {
        return editor.value?.chain().focus().run();
    }

    editor.value?.chain().focus().setLink({ href }).run();
};

defineExpose({
    focus: () => editor.value?.commands.focus(),
});
</script>

<template>
    <div v-if="editor" class="w-full">
        <menu :class="toolbarClass">
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        editor.isActive('bold')
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Negrito"
                    :aria-pressed="editor.isActive('bold')"
                    @click="editor.chain().focus().toggleBold().run()"
                >
                    <i class="ri-bold text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        editor.isActive('italic')
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Itálico"
                    :aria-pressed="editor.isActive('italic')"
                    @click="editor.chain().focus().toggleItalic().run()"
                >
                    <i class="ri-italic text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        editor.isActive('strike')
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Riscado"
                    :aria-pressed="editor.isActive('strike')"
                    @click="editor.chain().focus().toggleStrike().run()"
                >
                    <i class="ri-strikethrough text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        editor.isActive('blockquote')
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Citação"
                    :aria-pressed="editor.isActive('blockquote')"
                    @click="editor.chain().focus().toggleBlockquote().run()"
                >
                    <i class="ri-double-quotes-l text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        editor.isActive('bulletList')
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Lista com marcadores"
                    :aria-pressed="editor.isActive('bulletList')"
                    @click="editor.chain().focus().toggleBulletList().run()"
                >
                    <i class="ri-list-unordered text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        editor.isActive('orderedList')
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Lista ordenada"
                    :aria-pressed="editor.isActive('orderedList')"
                    @click="editor.chain().focus().toggleOrderedList().run()"
                >
                    <i class="ri-list-ordered text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        editor.isActive('link')
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Hiperligação"
                    :aria-pressed="editor.isActive('link')"
                    @click="promptUserForHref"
                >
                    <i class="ri-link text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        isHeadingActive(2)
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Cabeçalho nível 2"
                    :aria-pressed="isHeadingActive(2)"
                    @click="
                        editor.chain().focus().toggleHeading({ level: 2 }).run()
                    "
                >
                    <i class="ri-h-1 text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        isHeadingActive(3)
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Cabeçalho nível 3"
                    :aria-pressed="isHeadingActive(3)"
                    @click="
                        editor.chain().focus().toggleHeading({ level: 3 }).run()
                    "
                >
                    <i class="ri-h-2 text-base leading-none"></i>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="hover:cursor-pointer"
                    :class="[
                        toolbarButtonBase,
                        isHeadingActive(4)
                            ? toolbarButtonActive
                            : toolbarButtonInactive,
                    ]"
                    aria-label="Cabeçalho nível 4"
                    :aria-pressed="isHeadingActive(4)"
                    @click="
                        editor.chain().focus().toggleHeading({ level: 4 }).run()
                    "
                >
                    <i class="ri-h-3 text-base leading-none"></i>
                </button>
            </li>

            <slot
                name="toolbar"
                :editor="editor"
                :toolbar-button-base="toolbarButtonBase"
                :is-heading-active="isHeadingActive"
                :toolbar-button-active="toolbarButtonActive"
                :toolbar-button-inactive="toolbarButtonInactive"
            />
        </menu>

        <EditorContent :editor="editor" class="w-full" />
    </div>
</template>

<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
    color: hsl(var(--muted-foreground) / 80) !important;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}
</style>
