<script setup lang="ts">
import { AlertCircle, HelpCircle, Info, TriangleAlert } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

type MessageDialogType = 'info' | 'warning' | 'error' | 'confirm';

type ButtonVariant =
    | 'default'
    | 'destructive'
    | 'outline'
    | 'secondary'
    | 'ghost'
    | 'link';

interface Props {
    open?: boolean;
    type?: MessageDialogType;
    title: string;
    message: string;
    cancelText?: string;
    confirmText?: string;
    showCancelButton?: boolean;
    showConfirmButton?: boolean;
    confirmVariant?: ButtonVariant;
    cancelVariant?: ButtonVariant;
    closeOnConfirm?: boolean;
    closeOnCancel?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    open: undefined,
    type: 'confirm',
    cancelText: 'Cancel',
    confirmText: 'Confirm',
    showCancelButton: true,
    showConfirmButton: true,
    confirmVariant: undefined,
    cancelVariant: 'secondary',
    closeOnConfirm: true,
    closeOnCancel: true,
});

const emit = defineEmits<{
    'update:open': [value: boolean];
    confirm: [];
    cancel: [];
    close: [];
}>();

const internalOpen = ref(false);
const isControlled = computed(() => props.open !== undefined);

const dialogOpen = computed({
    get: () => {
        return isControlled.value ? props.open : internalOpen.value;
    },
    set: (value: boolean) => {
        if (isControlled.value) {
            emit('update:open', value);
        } else {
            internalOpen.value = value;
        }

        if (!value) {
            emit('close');
        }
    },
});

const iconComponent = computed(() => {
    const icons = {
        info: Info,
        warning: TriangleAlert,
        error: AlertCircle,
        confirm: HelpCircle,
    };

    return icons[props.type];
});

const iconClasses = computed(() => {
    const classes = {
        info: 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300',
        warning:
            'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-300',
        error: 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-300',
        confirm: 'bg-primary/10 text-primary',
    };

    return classes[props.type];
});

const resolvedConfirmVariant = computed<ButtonVariant>(() => {
    if (props.confirmVariant) {
        return props.confirmVariant;
    }

    if (props.type === 'error' || props.type === 'warning') {
        return 'destructive';
    }

    return 'default';
});

const handleCancel = () => {
    emit('cancel');

    if (props.closeOnCancel) {
        dialogOpen.value = false;
    }
};

const handleConfirm = () => {
    emit('confirm');

    if (props.closeOnConfirm) {
        dialogOpen.value = false;
    }
};
</script>

<template>
    <Dialog v-model:open="dialogOpen">
        <DialogTrigger v-if="$slots.trigger" as-child>
            <slot name="trigger" />
        </DialogTrigger>

        <DialogContent>
            <DialogHeader class="space-y-4">
                <div class="flex items-start gap-4">
                    <div
                        class="flex size-10 shrink-0 items-center justify-center rounded-full"
                        :class="iconClasses"
                    >
                        <component :is="iconComponent" class="size-5" />
                    </div>

                    <div class="space-y-2 text-left">
                        <DialogTitle>{{ title }}</DialogTitle>
                        <DialogDescription>
                            {{ message }}
                        </DialogDescription>
                    </div>
                </div>
            </DialogHeader>

            <slot />

            <DialogFooter class="gap-2">
                <DialogClose v-if="showCancelButton && closeOnCancel" as-child>
                    <Button
                        type="button"
                        :variant="cancelVariant"
                        @click="handleCancel"
                    >
                        {{ cancelText }}
                    </Button>
                </DialogClose>

                <Button
                    v-else-if="showCancelButton"
                    type="button"
                    :variant="cancelVariant"
                    @click="handleCancel"
                >
                    {{ cancelText }}
                </Button>

                <Button
                    v-if="showConfirmButton"
                    type="button"
                    :variant="resolvedConfirmVariant"
                    @click="handleConfirm"
                >
                    {{ confirmText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
