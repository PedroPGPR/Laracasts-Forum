import { readonly, ref } from 'vue';

type NotificationType = 'info' | 'warning' | 'error' | 'confirm';

type NotificationAction = false | (() => void | Promise<void>);

interface ConfirmNotificationOptions {
    type?: NotificationType;
    title: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    showCancelButton?: boolean;
    showConfirmButton?: boolean;
    closeOnConfirm?: boolean;
    closeOnCancel?: boolean;
    confirmAction?: NotificationAction;
    cancelAction?: NotificationAction;
}

const isOpen = ref(false);

const notification = ref<
    Required<
        Omit<ConfirmNotificationOptions, 'confirmAction' | 'cancelAction'>
    > & {
        confirmAction: NotificationAction;
        cancelAction: NotificationAction;
    }
>({
    type: 'confirm',
    title: '',
    message: '',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    showCancelButton: true,
    showConfirmButton: true,
    closeOnConfirm: true,
    closeOnCancel: true,
    confirmAction: false,
    cancelAction: false,
});

export function useNotification() {
    const confirmNotification = (options: ConfirmNotificationOptions) => {
        notification.value = {
            type: options.type ?? 'confirm',
            title: options.title,
            message: options.message,
            confirmText: options.confirmText ?? 'Confirm',
            cancelText: options.cancelText ?? 'Cancel',
            showCancelButton: options.showCancelButton ?? true,
            showConfirmButton: options.showConfirmButton ?? true,
            closeOnConfirm: options.closeOnConfirm ?? true,
            closeOnCancel: options.closeOnCancel ?? true,
            confirmAction: options.confirmAction ?? false,
            cancelAction: options.cancelAction ?? false,
        };

        isOpen.value = true;
    };

    const closeNotification = () => {
        isOpen.value = false;
    };

    const handleConfirm = async () => {
        if (notification.value.confirmAction) {
            await notification.value.confirmAction();
        }

        if (notification.value.closeOnConfirm) {
            closeNotification();
        }
    };

    const handleCancel = async () => {
        if (notification.value.cancelAction) {
            await notification.value.cancelAction();
        }

        if (notification.value.closeOnCancel) {
            closeNotification();
        }
    };

    return {
        isOpen,
        notification: readonly(notification),
        confirmNotification,
        closeNotification,
        handleConfirm,
        handleCancel,
    };
}
